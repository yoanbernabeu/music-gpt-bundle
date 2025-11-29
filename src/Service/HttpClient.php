<?php

declare(strict_types=1);

namespace YoanBernabeu\MusicGptBundle\Service;

use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use YoanBernabeu\MusicGptBundle\DTO\RequestInterface;
use YoanBernabeu\MusicGptBundle\DTO\ResponseInterface;
use YoanBernabeu\MusicGptBundle\Exception\ApiException;
use YoanBernabeu\MusicGptBundle\Exception\AuthenticationException;
use YoanBernabeu\MusicGptBundle\Exception\ConflictException;
use YoanBernabeu\MusicGptBundle\Exception\MusicGptException;
use YoanBernabeu\MusicGptBundle\Exception\NotFoundException;
use YoanBernabeu\MusicGptBundle\Exception\PaymentRequiredException;
use YoanBernabeu\MusicGptBundle\Exception\RateLimitException;
use YoanBernabeu\MusicGptBundle\Exception\ValidationException;

/**
 * Internal HTTP Client for Music GPT API.
 *
 * Handles authentication, HTTP requests, and error handling.
 * This service is internal and should not be used directly by end users.
 *
 * @internal
 */
class HttpClient
{
    public function __construct(
        private readonly HttpClientInterface $httpClient,
        private readonly string $baseUrl,
        private readonly string $apiKey,
    ) {
    }

    /**
     * Send a raw HTTP request to the API.
     *
     * For simple requests without DTOs. For complex requests, prefer sendRequest() with DTOs.
     *
     * @param array<string, mixed> $parameters
     *
     * @return array<string, mixed>
     *
     * @throws ApiException
     */
    public function request(string $endpoint, array $parameters = [], string $method = 'GET'): array
    {
        $url = rtrim($this->baseUrl, '/').'/'.ltrim($endpoint, '/');

        $options = [
            'headers' => [
                'Authorization' => $this->apiKey,
                'Accept' => 'application/json',
            ],
        ];

        if ('GET' === $method) {
            $options['query'] = $parameters;
        } else {
            $options['json'] = $parameters;
        }

        try {
            $response = $this->httpClient->request($method, $url, $options);
            $statusCode = $response->getStatusCode();

            if ($statusCode >= 400) {
                $this->handleErrorResponse($response->toArray(false), $statusCode, $endpoint);
            }

            return $response->toArray();
        } catch (ClientExceptionInterface $e) {
            $statusCode = $e->getResponse()->getStatusCode();
            $responseData = $e->getResponse()->toArray(false);

            $this->handleErrorResponse($responseData, $statusCode, $endpoint);
        } catch (ServerExceptionInterface $e) {
            throw new ApiException(sprintf('Server error on endpoint "%s": %s', $endpoint, $e->getMessage()), $e->getResponse()->getStatusCode(), $endpoint, $e);
        } catch (MusicGptException $e) {
            // Re-throw our custom exceptions (from handleErrorResponse)
            throw $e;
        } catch (\Exception $e) {
            throw new ApiException(sprintf('Failed to request Music GPT API endpoint "%s": %s', $endpoint, $e->getMessage()), 0, $endpoint, $e);
        }
    }

    /**
     * Send a request using a DTO.
     *
     * @internal this method is used by specialized services
     *
     * @template T of ResponseInterface
     *
     * @param class-string<T> $responseClass
     *
     * @return T
     *
     * @phpstan-return T
     */
    public function sendRequest(RequestInterface $request, string $responseClass): ResponseInterface
    {
        $responseData = $this->request(
            $request->getEndpoint(),
            $request->toArray(),
            $request->getMethod()
        );

        /* @var T */
        return $responseClass::fromArray($responseData);
    }

    /**
     * Handle error responses from the API.
     *
     * @param array<string, mixed> $data
     *
     * @throws ApiException
     */
    private function handleErrorResponse(array $data, int $statusCode, string $endpoint): never
    {
        $message = $data['message'] ?? $data['error'] ?? 'Unknown error';

        match ($statusCode) {
            400 => throw new ValidationException(sprintf('Bad Request: %s', $message), $data['errors'] ?? []),
            402 => throw new PaymentRequiredException($message),
            401, 403 => throw new AuthenticationException(sprintf('Authentication failed: %s', $message), $statusCode, $endpoint),
            404 => throw new NotFoundException($message, $endpoint),
            409 => throw new ConflictException($message, $endpoint),
            422 => throw new ValidationException(sprintf('Validation failed: %s', $message), $data['errors'] ?? []),
            429 => throw new RateLimitException($message, isset($data['retry_after']) ? (int) $data['retry_after'] : null),
            500 => throw new ApiException(sprintf('Internal Server Error: %s', $message), $statusCode, $endpoint),
            default => throw new ApiException($message, $statusCode, $endpoint),
        };
    }
}
