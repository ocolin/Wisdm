<?php

declare( strict_types = 1 );

namespace Ocolin\Wisdm;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Query;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use stdClass;

class HTTP
{

    /**
     * @var Client Guzzle HTTP Client.
     */
    public Client $client;

    /**
     * @var string API Base URI.
     */
    public string $base_uri;

    /**
     * @var string API Auth Token.
     */
    public string $token;

    /**
     * @var array<float|int|string> HTTP headers.
     */
    public array $headers;


/* CONSTRUCTOR
---------------------------------------------------------------------------- */

    /**
     * @param Client|null $client Guzzle HTTP client.
     * @param string|null $base_uri API Base URI.
     * @param string|null $token API Auth token.
     * @param bool $verify Option to verify SSL Certificate.
     * @param bool $errors Option to stop on HTTP errors.
     */
    public function __construct(
        ?Client $client     = null,
        ?string $base_uri   = null,
        ?string $token      = null,
           bool $verify     = false,
           bool $errors     = false,
            int $timeout    = 10,
    )
    {
        $this->base_uri = $base_uri ?? $_ENV['WISDM_BASE_URI'];
        $this->token    = $token    ?? $_ENV['WISDM_TOKEN'];
        $this->headers  = $this->default_Headers();

        $this->client   = $client   ?? new Client([
            'base_uri'        => $this->base_uri,
            'verify'          => $verify,
            'http_errors'     => $errors,
            'timeout'         => $timeout,
            'connect_timeout' => $timeout
        ]);
    }



/* GET METHOD
---------------------------------------------------------------------------- */

    /**
     * Retrieve an object or list of objects.
     *
     * @param string $uri URI of API call
     * @param array<string, string|int|float> $params GET params
     * @param array<string, mixed>|object|string|null $body POST Body
     * @param array<string, string|int|float> $headers Optional headers
     * @return object API response object
     * @throws GuzzleException
     */

    public function get(
         string $uri,
          array $params = [],
          array|object|string|null $body = [],
          array $headers = []
    ) : object
    {
        $this->headers = array_merge( $this->headers, $headers );
        $options = [
            'headers' => $this->headers,
              'query' => Query::build( $params )
        ];

        $request = $this->client->request(
             method: 'GET',
                uri: $this->base_uri . $uri,
            options: $options
        );

        return self::returnResults( request: $request );
    }



/* POST METHOD
---------------------------------------------------------------------------- */

    /**
     * Create an object.
     *
     * @param string $uri API URI.
     * @param array<string, string|int|float> $params HTTP GET parameters.
     * @param array<object>|object|null $body HTTP body parameters.
     * @param array<string, string|int|float> $headers Optional headers
     * @return object API response object
     * @throws GuzzleException
     */

    public function post(
        string $uri,
         array $params  = [],
         array|object|string|null $body = [],
         array $headers = []
    ) : object
    {
        $this->headers = array_merge( $this->headers, $headers );
        $request = $this->client->request(
             method: 'POST',
            uri: $this->base_uri . $uri,
            options: [
                'headers' => $this->headers,
                  'query' => Query::build( $params ) ?: [],
                   'body' => json_encode( value: $body )
            ]
        );

        return self::returnResults( request: $request );
    }



/* PATCH METHOD
---------------------------------------------------------------------------- */

    /**
     * Update an object.
     *
     * @param string $uri API URI.
     * @param array<string, string|int|float> $params HTTP GET parameters.
     * @param array<object>|object|null $body HTTP body parameters.
     * @param array<string, string|int|float> $headers Optional HTTP headers.
     * @return object API response object.
     * @throws GuzzleException
     */

    public function patch(
        string $uri,
         array $params  = [],
         array|object|string|null $body = [],
         array $headers = []
    ) : object
    {
        //$uri = self::formatParams( params: $params, uri: $uri );
        $this->headers = array_merge( $this->headers, $headers );
        $request = $this->client->request(
             method: 'PATCH',
                uri: $this->base_uri . $uri,
            options: [
                'headers' => $this->headers,
                  'query' => Query::build( $params ) ?: [],
                   'body' => json_encode( value: $body )
            ]
        );

        return self::returnResults( request: $request );
    }



/* DELETE METHOD
---------------------------------------------------------------------------- */

    /**
     * Delete an existing object.
     *
     * @param string $uri API URI.
     * @param array<string, string|int|float> $params HTTP GET parameters.
     * @param array<object> $body HTTP body parameters.
     * @param array<string, string|int|float> $headers Optional HTTP headers.
     * @return object API response object.
     * @throws GuzzleException
     */

    public function delete(
        string $uri,
         array $params = [],
         array|object|string|null $body = [],
         array $headers = []
    ) : object
    {
        $this->headers = array_merge( $this->headers, $headers );
        $request = $this->client->request(
             method: 'DELETE',
                uri: $this->base_uri . $uri,
            options: [ 'headers' => $this->headers ]
        );

        return self::returnResults( request: $request );
    }



/* FORMAT RESPONSE
---------------------------------------------------------------------------- */

    /**
     * Format the Guzzle HTTP request response into an array
     *
     * @param ResponseInterface $request Guzzle response object.
     * @return object API response object.
     */

    private static function returnResults( ResponseInterface $request ) : object
    {
        $response = new stdClass();
        $response->status = $request->getStatusCode();
        $response->status_message = $request->getReasonPhrase();
        $response->body = $request->getBody()->getContents();

        $headers = $request->getHeaders();

        if( str_starts_with(
            haystack: $headers['Content-Type'][0],
              needle: 'application/json' )
        ) {
            $response->body = json_decode( json: (string)$response->body );
        }

        return $response;
    }



/* DEFAULT SEND HEADERS
---------------------------------------------------------------------------- */

    /**
     * Generate a default set of headers so none are needed for most queries. Includes
     *  the authentication token which can be overridden with each call if specified.
     *
     *  @return array<string, string> Array of HTTP request headers
     */

    private function default_Headers() : array
    {
        return [
               'X-API-Key' => $this->token,
            'Content-type' => 'application/json; charset=utf-8',
              'User-Agent' => 'API Client 1.0',
        ];
    }
}