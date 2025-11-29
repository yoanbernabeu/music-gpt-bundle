<?php

declare(strict_types=1);

namespace YoanBernabeu\MusicGptBundle\Contract;

use YoanBernabeu\MusicGptBundle\DTO\ConversionDetails;
use YoanBernabeu\MusicGptBundle\Enum\ConversionType;
use YoanBernabeu\MusicGptBundle\Exception\MusicGptException;

/**
 * Interface for Conversion Service.
 *
 * Provides methods to retrieve conversion details.
 */
interface ConversionServiceInterface
{
    /**
     * Get conversion details by Task ID.
     *
     * @param string         $taskId         The task ID
     * @param ConversionType $conversionType The conversion type
     *
     * @throws MusicGptException If the request fails
     */
    public function getByTaskId(string $taskId, ConversionType $conversionType): ConversionDetails;

    /**
     * Get conversion details by Conversion ID.
     *
     * @param string         $conversionId   The conversion ID
     * @param ConversionType $conversionType The conversion type
     *
     * @throws MusicGptException If the request fails
     */
    public function getByConversionId(string $conversionId, ConversionType $conversionType): ConversionDetails;
}
