<?php

declare( strict_types = 1 );

namespace Ocolin\Wisdm;

readonly class Response
{
    /**
     * @param int $status HTTP status code.
     * @param string $statusMessage HTTP status message.
     * @param array<string, string[]> $headers HTTP response headers.
     * @param string $contentType HTTP response content type header.
     * @param mixed $body HTTP response body.
     */
    public function __construct(
        public int      $status,
        public string   $statusMessage,
        public array    $headers,
        public string   $contentType,
        public mixed    $body,
    ) {}
}