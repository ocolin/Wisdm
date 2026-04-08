<?php

declare( strict_types = 1 );

namespace Ocolin\Wisdm;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Ocolin\Wisdm\Exceptions\HttpException;
use Psr\Http\Message\ResponseInterface;

class HTTP
{
    /**
     * @var ClientInterface Guzzle client.
     */
    private ClientInterface $client;

    /**
     * List of valid HTTP methods for this API.
     */
    private const array VALID_METHODS = [
        'GET',
        'POST',
        'PATCH',
        'DELETE',
    ];


/* CONSTRUCTOR
----------------------------------------------------------------------------- */

    /**
     * @param Config $config Configuration data object.
     * @param ?ClientInterface $client Guzzle client for mocking.
     */
    public function __construct(
        readonly private Config $config,
        ?ClientInterface $client = null
    )
    {
        $this->client = $client ?? new Client(
            array_merge(
                $this->config->options,
                [
                    'base_uri' => rtrim(
                            string: $this->config->host, characters: '/'
                        ) . '/',
                    'http_errors' => false,
                    'headers'    => [
                        'X-API-Key' => $this->config->token,
                        'User-Agent' => 'Ocolin Client 3.0'
                    ],
                ]
            )
        );
    }



/* MAKE HTTP REQUEST
----------------------------------------------------------------------------- */

    /**
     * @param string $path URI endpoint path.
     * @param string $method HTTP method.
     * @param array<string, string|int|float|bool> $query HTTP query and path paremeters.
     * @param array<string, mixed> $body HTTP POST body parameters.
     * @return Response Client response object.
     * @throws GuzzleException|HttpException
     */
    public function request(
        string $path,
        string $method = 'GET',
         array $query = [],
         array $body = [],
    ) : Response
    {
        $method = strtoupper( string: $method );
        if( !in_array(
            needle: $method, haystack: self::VALID_METHODS, strict: true )
        ) {
            throw new HttpException(  message: "Invalid HTTP method: {$method}" );
        }

        $path = self::buildPath( path: $path, query: $query );

        $options = [];
        if( !empty( $query )) { $options['query'] = $query; }
        if( !empty( $body ))  { $options['json']  = $body;  }

        return self::buildResponse(
            response: $this->client->request(
                 method: $method,
                    uri: $path,
                options: $options,
            )
        );
    }



/* UPLOAD CONTENTS TO API
----------------------------------------------------------------------------- */

    /**
     * @param string $path URI endpoint path.
     * @param string $filePath Path to upload file.
     * @param array<string, mixed> $params POST body parameters.
     * @return Response Client response object.
     * @throws GuzzleException|HttpException
     */
    public function upload(
        string $path,
        string $filePath,
         array $params = []
    ): Response
    {
        $multi = [];
        $resource = fopen( filename: $filePath, mode: 'r' );
        if( $resource === false ) {
            throw new HttpException( message: "Cannot open file: {$filePath}" );
        }

        $multi[] = [
            'name'     => 'file',
            'contents' => $resource,
            'filename' => basename( path: $filePath )
        ];

        foreach( $params as $name => $value )
        {
            if( !is_scalar( $value)) {
                throw new HttpException( message: "Invalid param value: {$name}" );
            }
            $multi[] = [ 'name' => $name, 'contents' => (string)$value ];
        }

        $response =  self::buildResponse( $this->client->request(
             method: 'POST',
                uri: $path,
            options: [ 'multipart' => $multi ]
        ));
        fclose( $resource );

        return $response;
    }



/* BUILD URI PATH
----------------------------------------------------------------------------- */

    /**
     * Replaces any variable tokens in URI path and replaces with values.
     *
     * @param string $path HTTP URI path.
     * @param array<string, string|int|float|bool> $query HTTP query and path
            parameters.
     * @return string Interpolated URI path.
     */
    private static function buildPath( string $path, array &$query ): string
    {
        $path = ltrim( string: $path, characters: '/' );
        if( !str_contains( haystack: $path, needle: '{' )) { return $path; }

        foreach( $query as $key => $value )
        {
            if( str_contains( haystack: $path, needle: "{{$key}}" )) {
                $path = str_replace(
                    search: "{{$key}}", replace: (string)$value, subject: $path
                );
                unset( $query[$key] );
            }
        }

        return $path;
    }



/* BUILD HTTP RESPONSE OBJECT
----------------------------------------------------------------------------- */

    /**
     * @param ResponseInterface $response Guzzle PSR Response object.
     * @return Response API client response object.
     * @throws HttpException
     */
    private static function buildResponse( ResponseInterface $response ): Response
    {
        $contentType = $response->getHeaderLine( 'Content-Type' );
        if( str_contains( haystack: $contentType, needle: 'application/json' )) {
            $body = json_decode( json: $response->getBody()->getContents());

            if ( json_last_error() !== JSON_ERROR_NONE ) {
                throw new HttpException(
                    message: 'Failed to decode JSON response: ' . json_last_error_msg()
                );
            }
        }
        else {  $body = $response->getBody()->getContents(); }



        return new Response(
            status:        $response->getStatusCode(),
            statusMessage: $response->getReasonPhrase(),
            headers:       $response->getHeaders(),
            contentType:   $contentType,
            body:          $body,
        );
    }
}