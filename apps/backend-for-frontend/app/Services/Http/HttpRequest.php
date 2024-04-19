<?php

namespace App\Services\Http;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use JsonException;

class HttpRequest extends AbstractHttpRequest
{
        /**
     * The HTTP client instance.
     *
     * @var \Illuminate\Http\Client\PendingRequest
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
     *
     * @return \Illuminate\Http\Client\PendingRequest
     */
    public function getClient(): \Illuminate\Http\Client\PendingRequest
    {
        return $this->client;
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
     * @return mixed
     */
    public function send(string $method, string $uri, array $options = [])
    {
        try {
            $defaultOptions = [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ],
            ];

            $options = array_merge($defaultOptions, $options);

            $response = $this->getClient()->send($method, $uri, $options);

            if ($response->failed()) {
                $this->standardErrorResponse($response->status(), 'Service Error:' . ($response->json()['message'] ?? $response->body()));
            }
        } catch (\Exception $e) {
            $this->standardErrorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, 'Exception: ' . $e->getMessage());
        } catch (ClientException $e) {
            $this->standardErrorResponse($e->getCode(), 'Client Error: ' . $e->getMessage());
        } catch (ServerException $e) {
            $this->standardErrorResponse($e->getCode(), 'Server Error: ' . $e->getMessage(), true);
        }

        return $this->decodeBody($response);
    }

    /**
     * Standard error response.
     *
     * @param int $code
     * @param string $message
     * @param bool $externalError
     * @throws \Exception
     */
    protected function standardErrorResponse($code = 400, $message = '', $externalError = false)
    {
        $message = [
            'code' => $code,
            'message' => $message,
        ];

        Log::error(json_encode($message));

        if ($externalError) {
            throw new \Exception(json_encode($message), Response::HTTP_BAD_REQUEST);
        }

        throw new \Exception(json_encode($message), Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}