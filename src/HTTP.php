<?php

declare( strict_types = 1 );

namespace Ocolin\Wisdm;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Query;
use stdClass;

class HTTP
{

    public Client $client;

    public string $base_uri;

    public string $token;

    public array $headers;


/*
---------------------------------------------------------------------------- */

    public function __construct(
        ?Client $client     = null,
        ?string $base_uri   = null,
        ?string $token      = null,
           bool $verify     = false,
           bool $errors     = false
    )
    {
        $this->base_uri = $base_url ?? $_ENV['WISDM_BASE_URI'];
        $this->token    = $token    ?? $_ENV['WISDM_TOKEN'];
        $this->headers  = $this->default_Headers();

        $this->client   = $client   ?? new Client([
            'base_uri'        => $this->base_uri,
            'verify'          => $verify,
            'http_errors'     => $errors,
            'timeout'         => 30,
            'connect_timeout' => 30
        ]);
    }



/* GET METHOD
---------------------------------------------------------------------------- */

    /**
     * Retrieve an object or list of objects.
     *
     * @param string $uri
     * @param array<string, string|int|float> $params
     * @param array|null $body
     * @return object
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
     * @param string $uri
     * @param array<object>|object|null $body
     * @param array<string, string|int|float> $params
     * @return object
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
                  'query' => Query::build( $params ) ?? [],
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
     * @param string $uri
     * @param array<object>|object|null $body
     * @param array<string, string> $params
     * @return object
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
                'query' => Query::build( $params ) ?? [],
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
     * @param string $uri
     * @param array $params
     * @param array<object> $body
     * @return object
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
     * @param object $request
     * @return object
     */

    private static function returnResults( object $request ) : object
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



/* FORMAT URL PARAMETERS
---------------------------------------------------------------------------- */

    /**
     * Format an array of parameters into a URL query
     *
     * @param array<string, string|int|float> $params
     * @param string $uri
     * @return string final URI with parameters encoded into it, or plain uri.
     */

    private static function formatParams( array $params, string $uri ) : string
    {
        if( empty( $params )) { return $uri; }

        return $uri . '?' . http_build_query( $params );
    }
}