<?php

declare(strict_types=1);

namespace YoanBernabeu\MusicGptBundle\Enum;

/**
 * Enum for conversion types supported by MusicGPT API.
 *
 * @see https://docs.musicgpt.com/api-documentation/endpoint/getById
 */
enum ConversionType: string
{
    case MUSIC_AI = 'MUSIC_AI';
    case COVER = 'COVER';
    case TEXT_TO_SPEECH = 'TEXT_TO_SPEECH';
    case EXTRACTION = 'EXTRACTION';
}
