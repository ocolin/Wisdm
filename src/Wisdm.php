<?php

declare( strict_types = 1 );

namespace Ocolin\Wisdm;

use GuzzleHttp\Client;
use Ocolin\Env\EasyEnv;

class Wisdm
{
    public HTTP $http;

    public string $path;

    public array $params;

    public array $body;


/*
---------------------------------------------------------------------------- */

    public function __construct(
          ?Client $client   = null,
          ?string $base_uri = null,
          ?string $token    = null,
    )
    {
        if( !isset( $_ENV['WISDM_BASE_URI']) OR !isset( $_ENV['WISDM_TOKEN'] ) ) {
            EasyEnv::loadEnv( path: __DIR__ . '/../.env', append: true );
        }
        $this->http = new HTTP(
              client: $client,
            base_uri: $base_uri,
               token: $token
        );
    }



/*
---------------------------------------------------------------------------- */

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


/*
---------------------------------------------------------------------------- */

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

/*
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