<?php

declare( strict_types = 1 );

namespace Ocolin\Wisdm\Tests\Unit;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Response as GuzzleResponse;
use Ocolin\Wisdm\Config;
use Ocolin\Wisdm\Exceptions\ConfigException;
use Ocolin\Wisdm\Exceptions\HttpException;
use Ocolin\Wisdm\HTTP;
use Ocolin\Wisdm\Response;
use Ocolin\Wisdm\Wisdm;
use PHPUnit\Framework\TestCase;


class WisdmTest extends TestCase
{

/* CONFIG TESTS
----------------------------------------------------------------------------- */

    public function test_config_uses_constructor_host() : void
    {
        $config = new Config( host: 'https://custom.host.com', token: 'abc' );
        $this->assertSame( 'https://custom.host.com', $config->host );
    }

    public function test_config_uses_constructor_token() : void
    {
        $config = new Config( host: 'https://custom.host.com', token: 'mytoken' );
        $this->assertSame( 'mytoken', $config->token );
    }

    public function test_config_falls_back_to_env_host() : void
    {
        $config = new Config( token: 'abc' );
        $this->assertSame( getenv( 'WISDM_API_HOST' ), $config->host );
    }

    public function test_config_falls_back_to_env_token() : void
    {
        $config = new Config( host: 'https://custom.host.com' );
        $this->assertSame( getenv( 'WISDM_API_KEY' ), $config->token );
    }

    public function test_config_throws_when_host_missing() : void
    {
        unset( $_ENV['WISDM_API_HOST']);
        $this->expectException( ConfigException::class );
        $this->expectExceptionMessage( 'Hostname is not set.' );
        new Config( host: null, token: 'abc' );
        $_ENV['WISDM_API_HOST'] = 'https://wisdm.wirelesscoverage.com/api';
    }

    public function test_config_throws_when_token_missing() : void
    {
        unset( $_ENV['WISDM_API_KEY']);
        $this->expectException( ConfigException::class );
        $this->expectExceptionMessage( 'Token is not set.' );
        new Config( host: 'https://custom.host.com', token: null );
        $_ENV['WISDM_API_KEY'] = 'ABCDEFG';
    }

    public function test_config_sets_default_timeout() : void
    {
        $config = new Config( host: 'https://custom.host.com', token: 'abc' );
        $this->assertSame( 20, $config->options['timeout'] );
    }

    public function test_config_sets_default_verify() : void
    {
        $config = new Config( host: 'https://custom.host.com', token: 'abc' );
        $this->assertTrue( $config->options['verify'] );
    }

    public function test_config_caller_options_override_defaults() : void
    {
        $config = new Config(
            host: 'https://custom.host.com',
            token: 'abc',
            options: [ 'timeout' => 60 ]
        );
        $this->assertSame( 60, $config->options['timeout'] );
    }

    public function test_config_strips_headers_from_options() : void
    {
        $config = new Config(
            host: 'https://custom.host.com',
            token: 'abc',
            options: [ 'headers' => [ 'X-Custom' => 'value' ] ]
        );
        $this->assertArrayNotHasKey( 'headers', $config->options );
    }


/* RESPONSE TESTS
----------------------------------------------------------------------------- */

    public function test_response_holds_status_code() : void
    {
        $response = new Response(
            status:        200,
            statusMessage: 'OK',
            headers:       [],
            contentType:   'application/json',
            body:          null
        );
        $this->assertSame( 200, $response->status );
    }

    /*
    public function test_response_holds_content_type() : void
    {
        $response = new Response(
            status:        200,
            statusMessage: 'OK',
            headers:       [],
            contentType:   'text/csv',
            body:          'col1,col2'
        );
        $this->assertSame( 'text/csv', $response->contentType );
    }
    */

    public function test_response_holds_raw_body_for_non_json() : void
    {
        $response = new Response(
            status:        200,
            statusMessage: 'OK',
            headers:       [],
            contentType:   'text/csv',
            body:          'col1,col2'
        );
        $this->assertSame( 'col1,col2', $response->body );
    }


/* HTTP REQUEST TESTS
----------------------------------------------------------------------------- */

    public function test_request_throws_on_invalid_method() : void
    {
        $config = new Config( host: 'https://fake.host.com', token: 'abc' );
        $http   = new HTTP( config: $config, client: $this->mockClient() );

        $this->expectException( HttpException::class );
        $this->expectExceptionMessage( 'Invalid HTTP method: PUT' );
        $http->request( path: '/test', method: 'PUT' );
    }

    public function test_request_returns_response_object() : void
    {
        $config = new Config( host: 'https://fake.host.com', token: 'abc' );
        $http   = new HTTP( config: $config, client: $this->mockJsonClient() );

        $response = $http->request( path: '/test' );
        $this->assertInstanceOf( Response::class, $response );
    }

    public function test_request_decodes_json_response() : void
    {
        $config = new Config( host: 'https://fake.host.com', token: 'abc' );
        $http   = new HTTP( config: $config, client: $this->mockJsonClient(
            body: '{"id":1,"name":"test"}'
        ));

        $response = $http->request( path: '/test' );
        $this->assertInstanceOf( \stdClass::class, $response->body );
        $this->assertSame( 1, $response->body->id );
    }

    public function test_request_returns_raw_body_for_csv_response() : void
    {
        $config = new Config( host: 'https://fake.host.com', token: 'abc' );
        $http   = new HTTP( config: $config, client: $this->mockClient(
            contentType: 'text/csv',
            body:        'col1,col2\nval1,val2'
        ));

        $response = $http->request( path: '/test' );
        $this->assertIsString( $response->body );
    }

    public function test_request_returns_raw_body_for_binary_response() : void
    {
        $config = new Config( host: 'https://fake.host.com', token: 'abc' );
        $http   = new HTTP( config: $config, client: $this->mockClient(
            contentType: 'image/png',
            body:        'fakebinarydata'
        ));

        $response = $http->request( path: '/test' );
        $this->assertIsString( $response->body );
        $this->assertSame( 'image/png', $response->contentType );
    }

    public function test_request_substitutes_path_tokens() : void
    {
        $captured = [];
        $mock     = $this->createMock( ClientInterface::class );
        $mock->expects( $this->once() )
            ->method( 'request' )
            ->willReturnCallback( function( $method, $uri, $options ) use ( &$captured ) {
                $captured['uri'] = $uri;
                return new GuzzleResponse( 200, ['Content-Type' => 'application/json'], '{}' );
            });

        $config   = new Config( host: 'https://fake.host.com', token: 'abc' );
        $http     = new HTTP( config: $config, client: $mock );
        $http->request( path: '/antennas/{id}', query: ['id' => 42] );

        $this->assertSame( 'antennas/42', $captured['uri'] );
    }

    public function test_request_removes_path_tokens_from_query_string() : void
    {
        $captured = [];
        $mock     = $this->createMock( ClientInterface::class );
        $mock->expects( $this->once() )
            ->method( 'request' )
            ->willReturnCallback( function( $method, $uri, $options ) use ( &$captured ) {
                $captured['options'] = $options;
                return new GuzzleResponse( 200, ['Content-Type' => 'application/json'], '{}' );
            });

        $config = new Config( host: 'https://fake.host.com', token: 'abc' );
        $http   = new HTTP( config: $config, client: $mock );
        $http->request( path: '/antennas/{id}', query: ['id' => 42] );

        $this->assertArrayNotHasKey( 'query', $captured['options'] );
    }

    public function test_request_sends_json_body() : void
    {
        $captured = [];
        $mock     = $this->createMock( ClientInterface::class );
        $mock->expects( $this->once() )
            ->method( 'request' )
            ->willReturnCallback( function( $method, $uri, $options ) use ( &$captured ) {
                $captured['options'] = $options;
                return new GuzzleResponse( 200, ['Content-Type' => 'application/json'], '{}' );
            });

        $config = new Config( host: 'https://fake.host.com', token: 'abc' );
        $http   = new HTTP( config: $config, client: $mock );
        $http->request( path: '/networks', method: 'POST', body: ['name' => 'test'] );

        $this->assertArrayHasKey( 'json', $captured['options'] );
        $this->assertSame( ['name' => 'test'], $captured['options']['json'] );
    }

    public function test_request_handles_error_response_as_json() : void
    {
        $config = new Config( host: 'https://fake.host.com', token: 'abc' );
        $http   = new HTTP( config: $config, client: $this->mockJsonClient(
            status: 404,
            body:   '{"error":"not_found"}'
        ));

        $response = $http->request( path: '/antennas/99999' );
        $this->assertSame( 404, $response->status );
        $this->assertSame( 'not_found', $response->body->error );
    }

    public function test_request_throws_on_invalid_json_response() : void
    {
        $config = new Config( host: 'https://fake.host.com', token: 'abc' );
        $http   = new HTTP( config: $config, client: $this->mockJsonClient(
            body: 'not valid json {'
        ));

        $this->expectException( HttpException::class );
        $this->expectExceptionMessage( 'Failed to decode JSON response' );
        $http->request( path: '/test' );
    }


/* UPLOAD TESTS
----------------------------------------------------------------------------- */

    /*
    public function test_upload_throws_on_unreadable_file() : void
    {
        $config = new Config( host: 'https://fake.host.com', token: 'abc' );
        $http   = new HTTP( config: $config, client: $this->mockClient() );

        $this->expectException( HttpException::class );
        $this->expectExceptionMessage( 'Cannot open file' );
        $http->upload( path: '/accounts/logo', filePath: '/nonexistent/file.csv' );
    }
    */

    public function test_upload_throws_on_non_scalar_param() : void
    {
        $config  = new Config( host: 'https://fake.host.com', token: 'abc' );
        $http    = new HTTP( config: $config, client: $this->mockJsonClient() );
        $tmpFile = $this->createTempFile();

        $this->expectException( HttpException::class );
        $this->expectExceptionMessage( 'Invalid param value' );
        $http->upload(
            path:     '/networks/import-networks',
            filePath: $tmpFile,
            params:   [ 'colour' => ['not', 'scalar'] ]
        );
    }

    public function test_upload_returns_response_object() : void
    {
        $config  = new Config( host: 'https://fake.host.com', token: 'abc' );
        $http    = new HTTP( config: $config, client: $this->mockJsonClient() );
        $tmpFile = $this->createTempFile();

        $response = $http->upload( path: '/accounts/logo', filePath: $tmpFile );
        $this->assertInstanceOf( Response::class, $response );
    }

    public function test_upload_sends_multipart_request() : void
    {
        $captured = [];
        $mock     = $this->createMock( ClientInterface::class );
        $mock->expects( $this->once() )
            ->method( 'request' )
            ->willReturnCallback( function( $method, $uri, $options ) use ( &$captured ) {
                $captured['options'] = $options;
                return new GuzzleResponse( 200, ['Content-Type' => 'application/json'], '{}' );
            });

        $config  = new Config( host: 'https://fake.host.com', token: 'abc' );
        $http    = new HTTP( config: $config, client: $mock );
        $tmpFile = $this->createTempFile();
        $http->upload( path: '/accounts/logo', filePath: $tmpFile );

        $this->assertArrayHasKey( 'multipart', $captured['options'] );
    }

    public function test_upload_includes_extra_params_in_multipart() : void
    {
        $captured = [];
        $mock     = $this->createMock( ClientInterface::class );
        $mock->expects( $this->once() )
            ->method( 'request' )
            ->willReturnCallback( function( $method, $uri, $options ) use ( &$captured ) {
                $captured['options'] = $options;
                return new GuzzleResponse( 200, ['Content-Type' => 'application/json'], '{}' );
            });

        $config  = new Config( host: 'https://fake.host.com', token: 'abc' );
        $http    = new HTTP( config: $config, client: $mock );
        $tmpFile = $this->createTempFile();
        $http->upload(
            path:     '/networks/import-networks',
            filePath: $tmpFile,
            params:   [ 'colour' => '#ff0000' ]
        );

        $names = array_column( $captured['options']['multipart'], 'name' );
        $this->assertContains( 'file', $names );
        $this->assertContains( 'colour', $names );
    }


/* WISDM WRAPPER TESTS
----------------------------------------------------------------------------- */

    public function test_wisdm_get_calls_http_request_with_get_method() : void
    {
        $_ENV['WISDM_API_HOST'] = 'https://fake.host';
        $_ENV['WISDM_API_KEY'] = 'abc';
        $http = $this->createMock( HTTP::class );
        $http->expects( $this->once() )
            ->method( 'request' )
            ->with(
                path:   '/antennas',
                method: 'GET',
                query:  [],
                body:   []
            )
            ->willReturn( $this->fakeResponse() );

        $wisdm = new Wisdm( http: $http );
        $wisdm->get( '/antennas' );
    }

    public function test_wisdm_post_calls_http_request_with_post_method() : void
    {
        $http = $this->createMock( HTTP::class );
        $http->expects( $this->once() )
            ->method( 'request' )
            ->with(
                path:   '/networks',
                method: 'POST',
                query:  [],
                body:   ['name' => 'test']
            )
            ->willReturn( $this->fakeResponse() );

        $wisdm = new Wisdm( http: $http );
        $wisdm->post( '/networks', body: ['name' => 'test'] );
    }

    public function test_wisdm_patch_calls_http_request_with_patch_method() : void
    {
        $http = $this->createMock( HTTP::class );
        $http->expects( $this->once() )
            ->method( 'request' )
            ->with(
                path:   '/networks/1',
                method: 'PATCH',
                query:  [],
                body:   ['name' => 'updated']
            )
            ->willReturn( $this->fakeResponse() );

        $wisdm = new Wisdm( http: $http );
        $wisdm->patch( '/networks/1', body: ['name' => 'updated'] );
    }

    public function test_wisdm_delete_calls_http_request_with_delete_method() : void
    {
        $http = $this->createMock( HTTP::class );
        $http->expects( $this->once() )
            ->method( 'request' )
            ->with(
                path:   '/networks/1',
                method: 'DELETE',
                query:  [],
                body:   []
            )
            ->willReturn( $this->fakeResponse() );

        $wisdm = new Wisdm( http: $http );
        $wisdm->delete( '/networks/1' );
    }

    public function test_wisdm_upload_calls_http_upload() : void
    {
        $http = $this->createMock( HTTP::class );
        $http->expects( $this->once() )
            ->method( 'upload' )
            ->with(
                path:     '/accounts/logo',
                filePath: '/tmp/logo.png',
                params:   []
            )
            ->willReturn( $this->fakeResponse() );

        $wisdm = new Wisdm( http: $http );
        $wisdm->upload( '/accounts/logo', '/tmp/logo.png' );
    }

    public function test_wisdm_request_passes_through_custom_method() : void
    {
        $http = $this->createMock( HTTP::class );
        $http->expects( $this->once() )
            ->method( 'request' )
            ->with(
                path:   '/test',
                method: 'DELETE',
                query:  [],
                body:   []
            )
            ->willReturn( $this->fakeResponse() );

        $wisdm = new Wisdm( http: $http );
        $wisdm->request( '/test', 'DELETE' );
    }


/* HELPERS
----------------------------------------------------------------------------- */

    private function mockClient(
        int    $status      = 200,
        string $contentType = 'text/plain',
        string $body        = ''
    ) : ClientInterface
    {
        $mock = $this->createStub( ClientInterface::class );
        $mock->method( 'request' )->willReturn(
            new GuzzleResponse( $status, ['Content-Type' => $contentType], $body )
        );
        return $mock;
    }

    private function mockJsonClient(
        int    $status = 200,
        string $body   = '{}'
    ) : ClientInterface
    {
        return $this->mockClient(
            status:      $status,
            contentType: 'application/json; charset=utf-8',
            body:        $body
        );
    }

    private function fakeResponse() : Response
    {
        return new Response(
            status:        200,
            statusMessage: 'OK',
            headers:       [],
            contentType:   'application/json',
            body:          new \stdClass()
        );
    }

    private function createTempFile() : string
    {
        $path = tempnam( sys_get_temp_dir(), 'wisdm_test_' );
        file_put_contents( $path, 'test,data' );
        return $path;
    }
}