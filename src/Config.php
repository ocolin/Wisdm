<?php

declare( strict_types = 1 );

namespace Ocolin\Wisdm;

use Ocolin\GlobalType\ENV;
use Ocolin\Wisdm\Exceptions\ConfigException;

readonly class Config
{
    /**
     * @var string Host of WISDM API server.
     */
    public string $host;

    /**
     * @var string API authentication token.
     */
    public string $token;

    /**
     * @var array<string, mixed> Guzzle optional parameters.
     */
    public array $options;

    /**
     * @param string|null $host
     * @param string|null $token
     * @param array<string, mixed> $options
     * @throws ConfigException
     */
    public function __construct(
        ?string $host    = null,
        ?string $token   = null,
          array $options = []
    )
    {
        $this->host = $host ?? ENV::getStringNull( name: 'WISDM_API_HOST' )
            ?? throw new ConfigException( message: 'Hostname is not set.' );

        $this->token = $token ?? ENV::getStringNull( name: 'WISDM_API_KEY' )
            ?? throw new ConfigException( message: 'Token is not set.' );

        unset( $options['headers'] );
        $this->options = array_merge(  [ 'timeout' => 20, 'verify' => true ], $options );
    }
}