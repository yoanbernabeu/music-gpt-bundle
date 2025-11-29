<?php

declare(strict_types=1);

namespace YoanBernabeu\MusicGptBundle\Service;

use YoanBernabeu\MusicGptBundle\Contract\ConversionServiceInterface;
use YoanBernabeu\MusicGptBundle\DTO\ConversionDetails;
use YoanBernabeu\MusicGptBundle\Enum\ConversionType;

/**
 * Service for retrieving conversion details.
 *
 * @see https://docs.musicgpt.com/api-documentation/endpoint/getById
 */
class ConversionService implements ConversionServiceInterface
{
    public function __construct(
        private readonly HttpClient $httpClient
    ) {
    }

    public function getByTaskId(string $taskId, ConversionType $conversionType): ConversionDetails
    {
        $response = $this->httpClient->request(
            '/byId',
            [
                'conversionType' => $conversionType->value,
                'task_id' => $taskId,
            ],
            'GET'
        );

        return ConversionDetails::fromArray($response);
    }

    public function getByConversionId(string $conversionId, ConversionType $conversionType): ConversionDetails
    {
        $response = $this->httpClient->request(
            '/byId',
            [
                'conversionType' => $conversionType->value,
                'conversion_id' => $conversionId,
            ],
            'GET'
        );

        return ConversionDetails::fromArray($response);
    }
}
