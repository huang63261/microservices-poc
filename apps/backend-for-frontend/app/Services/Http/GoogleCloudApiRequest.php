<?php

namespace App\Services\Http;

use Google\Auth\ApplicationDefaultCredentials;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use JsonException;

class GoogleCloudApiRequest
{
    protected Client $client;

    public function __construct(
        string $targetAudience
    ) {
        $middleware = ApplicationDefaultCredentials::getIdTokenMiddleware($targetAudience);
        $stack = HandlerStack::create();
        $stack->push($middleware);

        $this->client = new Client([
            'handler' => $stack,
            'auth' => 'google_auth',
            // Cloud Run, IAP, or custom resource URL
            'base_uri' => $targetAudience,
        ]);
    }

    public function send(string $method, string $uri, array $options = [])
    {
        try {
            $response = $this->client->request($method, $uri, $options);

            return $this->decodeBody($response);
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ];
        }
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
}