<?php

declare( strict_types = 1 );

namespace Ocolin\Wisdm;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Ocolin\EasyEnv\LoadEnv;

class Wisdm
{
    /**
     * @var HTTP Guzzle HTTP object.
     */
    public HTTP $http;

    /**
     * @var string URI path
     */
    public string $path;

    /**
     * @var array<string, string|int|float> HTTP URI parameters
     */
    public array $params;

    /**
     * @var array<string, mixed>  HTTP request body
     */
    public array $body;


/* CONSTRUCTOR
---------------------------------------------------------------------------- */

    /**
     * @param Client|null $client Guzzle HTTP Client.
     * @param string|null $base_uri API Base URI.
     * @param string|null $token API Auth token.
     * @param int $timeout Because some calls take several minutes,
     *  this allows customer timeouts
     * @throws Exception
     */
    public function __construct(
          ?Client $client   = null,
          ?string $base_uri = null,
          ?string $token    = null,
              int $timeout = 10
    )
    {
        if( !isset( $_ENV['WISDM_BASE_URI']) OR !isset( $_ENV['WISDM_TOKEN'] ) ) {
            new LoadEnv( files: __DIR__ . '/../.env', append: true );
        }
        $this->http = new HTTP(
              client: $client,
            base_uri: $base_uri,
               token: $token,
             timeout: $timeout
        );
    }



/* MAKE API CALL
---------------------------------------------------------------------------- */

    /**
     * @param string $path - URI path of API call.
     * @param string $method - HTTP Method of API call.
     * @param array<string, string|int|float> $params - URL Parameters of API call.
     * @param array<string, mixed> $body - POST body content for API call.
     * @param array<string, string|int|float> $headers - Optional HTTP Headers to send.
     * @return object API response object.
     */
    public function call(
        string $path,
        string $method  = 'GET',
         array $params  = [],
         array $body    = [],
         array $headers = []
    ) : object
    {
        $this->path   = $path;
        $this->params = $params;
        $this->parse_URI();

        $method = strtolower( $method );

        return $this->http->$method(
                uri: $this->path,
             params: $this->params,
               body: $body,
            headers: $headers
        );
    }


/* CREATE AN OBJECT - POST METHOD
---------------------------------------------------------------------------- */

    /**
     * @param string $path - URI path of API call.
     * @param array<string, string|int|float> $params - URI parameters of API call.
     * @param array<string, mixed> $body - POST body parameters of API call.
     * @param array<string, string|int|float> $headers - Optional HTTP headers
     * @return object - API response object
     * @throws GuzzleException
     */
    public function create(
        string $path,
         array $params  = [],
         array $body    = [],
         array $headers = []
    ) : object
    {
        $this->path   = $path;
        $this->params = $params;
        $this->parse_URI();

        return $this->http->post(
                uri: $this->path,
             params: $this->params,
               body: $body,
            headers: $headers
        );
    }


/* GET AN OBJECT - GET METHOD
---------------------------------------------------------------------------- */

    /**
     * @param string $path - URI path of API call.
     * @param array<string, string|int|float> $params - URI parameters of API call.
     * @param array<string, mixed> $body - POST body parameters of API call.
     * @param array<string, string|int|float> $headers - Optional headers.
     * @return object - API response object.
     * @throws GuzzleException
     */
    public function get(
        string $path,
         array $params  = [],
         array $body    = [],
         array $headers = []
    ) : object
    {
        $this->path   = $path;
        $this->params = $params;
        $this->parse_URI();

        return $this->http->get(
                uri: $this->path,
             params: $this->params,
               body: $body,
            headers: $headers
        );
    }



/* UPDATE AN OBJECT - PATCH METHOD
---------------------------------------------------------------------------- */

    /**
     * @param string $path - URI of API call.
     * @param array<string, string|int|float> $params - URI parameters of API call.
     * @param array<string, mixed> $body - POST body parameters of API call.
     * @param array<string, string|int|float> $headers - Optional HTTP headers.
     * @return object - API response object.
     * @throws GuzzleException
     */
    public function update(
        string $path,
         array $params  = [],
         array $body    = [],
         array $headers = []
    ) : object
    {
        $this->path   = $path;
        $this->params = $params;
        $this->parse_URI();

        return $this->http->patch(
                uri: $this->path,
             params: $this->params,
               body: $body,
            headers: $headers
        );
    }



/* DELETE OBJECT - DELETE METHOD
---------------------------------------------------------------------------- */

    /**
     * @param string $path - URI of API call.
     * @param array<string, string|int|float> $params - URI parameters of API call.
     * @param array<string, mixed> $body - POST body parameters of API call.
     * @param array<string, string|int|float> $headers - Optional HTTP headers.
     * @return object - API response object.
     * @throws GuzzleException
     */
    public function delete(
        string $path,
         array $params  = [],
         array $body    = [],
         array $headers = []
    ) : object
    {
        $this->path   = $path;
        $this->params = $params;
        $this->parse_URI();

        return $this->http->delete(
                uri: $this->path,
             params: $this->params,
               body: $body,
            headers: $headers
        );
    }



/* PARSE API URI
---------------------------------------------------------------------------- */

    private function parse_URI() : void
    {
        if( str_contains( haystack: $this->path, needle: '{' )) {
            foreach( $this->params as $key => $value ) {
                if( str_contains( haystack: $this->path, needle: '{' . $key . '}' )) {
                    $this->path = str_replace(
                         search: '{' . $key . '}',
                        replace: (string)$value,
                        subject: $this->path
                    );
                    unset( $this->params[$key] );
                }
            }
        }
    }
}