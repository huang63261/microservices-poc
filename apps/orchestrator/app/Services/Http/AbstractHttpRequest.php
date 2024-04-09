<?php

namespace App\Services\Http;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use JsonException;

abstract class AbstractHttpRequest
{
    /**
     * The HTTP client instance.
     *
     * @var \Illuminate\Http\Client\PendingRequest
     */
    protected $http;

    /**
     * Set the HTTP client instance.
     *
     * @param  \Illuminate\Http\Client\PendingRequest  $http
     */
    public function setHttp(\Illuminate\Http\Client\PendingRequest $http): void
    {
        $this->http = $http;
    }


    /**
     * Get the HTTP client instance.
     *
     * @return \Illuminate\Http\Client\PendingRequest
     */
    public function getHttp(): \Illuminate\Http\Client\PendingRequest
    {
        return $this->http;
    }

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
     * @param  string  $method
     * @param  string  $uri
     * @param  array  $options
     * @param  array  $queryParams
     * @return mixed
     */
    public function send(string $method, string $uri, array $options = [], array $queryParams = [])
    {
        try {
            $defaultOptions = [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ],
            ];

            $options = array_merge($defaultOptions, $options);

            if (!empty($queryParams)) {
                $uri .= '?' . http_build_query($queryParams);
            }

            $response = $this->getHttp()->send($method, $uri, $options);

            if ($response->failed()) {
                $this->standardErrorResponse($response->getStatusCode(), $response->json()['message'] ?? '');
            }
        } catch (ClientException $e) {
            $this->standardErrorResponse($e->getCode(), $e->getMessage());
        }

        return $this->decodeBody($response);
    }

    /**
     * Standard error response.
     *
     * @param int $code
     * @param string $message
     * @throws Exception
     */
    public function standardErrorResponse($code = 400, $message = '')
    {
        $message = [
            'code' => $code,
            'message' => $message,
        ];

        Log::error('Error: ' . json_encode($message));

        throw new Exception(json_encode($message), Response::HTTP_BAD_REQUEST);
    }
}
