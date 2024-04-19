<?php

namespace App\Services\Http;

use JsonException;

abstract class AbstractHttpRequest
{
    /**
     * The HTTP client instance.
     */
    protected $client;

    /**
     * Create a new instance.
     *
     * @param  mixed  $client
     */
    public function __construct($client)
    {
        $this->client = $client;
    }

    /**
     * Get the HTTP client instance.
     */
    abstract public function getClient();

    /**
     * Decode the body of the response.
     *
     * @param  \Psr\Http\Message\ResponseInterface  $response
     * @return mixed
     */
    public function decodeBody($response)
    {
        try {
            return json_decode(
                json: $response->getBody(),
                associative: true,
                flags: JSON_THROW_ON_ERROR
            );
        } catch (JsonException $je) {
            return $response->getBody()->getContents();
        }
    }

    /**
     * Send a request to the server.
     *
     * @param string $method
     * @param string $uri
     * @param array $options
     */
    abstract public function send(string $method, string $uri, array $options = []);

    /**
     * Standard error response.
     */
    abstract protected function standardErrorResponse();
}
