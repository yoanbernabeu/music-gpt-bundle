<?php

declare(strict_types=1);

namespace YoanBernabeu\MusicGptBundle\Service;

use YoanBernabeu\MusicGptBundle\Contract\VoiceServiceInterface;
use YoanBernabeu\MusicGptBundle\DTO\Voice\VoicesResponse;

/**
 * Voice Service.
 *
 * Manages voice listings and search functionality.
 */
class VoiceService implements VoiceServiceInterface
{
    public function __construct(
        private readonly HttpClient $httpClient,
    ) {
    }

    public function searchVoices(string $query, int $limit = 20, int $page = 0): VoicesResponse
    {
        $data = $this->httpClient->request(
            '/searchVoices',
            [
                'query' => $query,
                'limit' => $limit,
                'page' => $page,
            ],
            'GET'
        );

        return VoicesResponse::fromArray($data);
    }

    public function getAllVoices(int $limit = 20, int $page = 0): VoicesResponse
    {
        $data = $this->httpClient->request(
            '/getAllVoices',
            [
                'limit' => $limit,
                'page' => $page,
            ],
            'GET'
        );

        return VoicesResponse::fromArray($data);
    }
}
