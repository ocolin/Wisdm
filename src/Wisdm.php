<?php

declare( strict_types = 1 );

namespace Ocolin\Wisdm;

use Ocolin\Wisdm\Exceptions\HttpException;
use GuzzleHttp\Exception\GuzzleException;

class Wisdm
{
    private HTTP $http;


/* CONSTRUCTOR
----------------------------------------------------------------------------- */

    /**
     * @param ?Config $config Data configuration object.
     * @param ?HTTP $http Guzzle HTTP client for mocking.
     */
    public function __construct(
        ?Config $config = null,
        ?HTTP $http = null
    )
    {
        $config     = $config ?? new Config();
        $this->http = $http   ?? new HTTP( config: $config );
    }



/* GET REQUEST
----------------------------------------------------------------------------- */

    /**
     * @param string $endpoint API end point.
     * @param array<string, string|int|float|bool>|object $query HTTP query
     *   or path parameters.
     * @return Response Client A response object.
     * @throws GuzzleException|HttpException
     */
    public function get( string $endpoint, array|object $query = [] ) : Response
    {
        return $this->http->request( path: $endpoint, query: (array)$query );
    }



/* POST (CREATE) REQUEST
----------------------------------------------------------------------------- */

    /**
     * @param string $endpoint API end point.
     * @param array<string, string|int|float|bool>|object $query HTTP
     *   query or path parameters.
     * @param array<string, mixed>|object $body HTTP POST body parameters.
     * @return Response Client A response object.
     * @throws GuzzleException|HttpException
     */
    public function post(
              string $endpoint,
        array|object $query = [],
        array|object $body = []
    ) : Response
    {
        return $this->http->request(
              path: $endpoint,
            method: 'POST',
             query: (array)$query,
              body: (array)$body
        );
    }



/* PATCH (UPDATE) REQUEST
----------------------------------------------------------------------------- */

    /**
     * @param string $endpoint API end point.
     * @param array<string, string|int|float|bool>|object $query HTTP query
     *    or path parameters.
     * @param array<string, mixed>|object $body HTTP POST body parameters.
     * @return Response Client A response object.
     * @throws GuzzleException|HttpException
     */
    public function patch(
              string $endpoint,
        array|object $query = [],
        array|object $body = []
    ) : Response
    {
        return $this->http->request(
              path: $endpoint,
            method: 'PATCH',
             query: (array)$query,
              body: (array)$body
        );
    }



/* DELETE REQUEST
----------------------------------------------------------------------------- */

    /**
     * @param string $endpoint API end point.
     * @param array<string, string|int|float|bool>|object $query HTTP query
     *    or path parameters.
     * @return Response Client A response object.
     * @throws GuzzleException|HttpException
     */
    public function delete( string $endpoint, array|object $query = [] ) : Response
    {
        return $this->http->request(
              path: $endpoint,
            method: 'DELETE',
             query: (array)$query
        );
    }



/* GENERAL REQUEST
----------------------------------------------------------------------------- */

    /**
     * @param string $endpoint API end point.
     * @param string $method HTTP method to send.
     * @param array<string, string|int|float|bool>|object $query HTTP query
     *    or path parameters.
     * @param array<string, mixed>|object $body HTTP POST body parameters.
     * @return Response Client A response object.
     * @throws GuzzleException|HttpException
     */
    public function request(
              string $endpoint,
              string $method,
        array|object $query = [],
        array|object $body = []
    ) : Response
    {
        return $this->http->request(
              path: $endpoint,
            method: $method,
             query: (array)$query,
              body: (array)$body
        );
    }

/* UPLOAD FILES
----------------------------------------------------------------------------- */

    /**
     * @param string $endpoint API end point.
     * @param string $filePath Path to files for uploading.
     * @param array<string, mixed>|object $params HTTP POST body parameters.
     * @return Response Client A response object.
     * @throws HttpException|GuzzleException
     */
    public function upload(
              string $endpoint,
              string $filePath,
        array|object $params = []
    ) : Response
    {
        return $this->http->upload(
                path: $endpoint,
            filePath: $filePath,
              params: (array)$params
        );
    }
}