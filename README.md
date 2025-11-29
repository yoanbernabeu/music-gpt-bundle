# Music GPT Bundle

A Symfony bundle for integrating [Music GPT API](https://musicgpt.com/api) into your applications.

> **🎵 Powered by [MusicGPT.com](https://musicgpt.com)** - Generate AI music and covers  
> **🔌 API Access**: [musicgpt.com/api](https://musicgpt.com/api)

[![PHP Version](https://img.shields.io/badge/php-%3E%3D8.4-blue.svg)](https://php.net/)
[![Symfony](https://img.shields.io/badge/symfony-%5E7.0%7C%5E8.0-green.svg)](https://symfony.com/)
[![License](https://img.shields.io/badge/license-MIT-brightgreen.svg)](LICENSE)
[![QA](https://github.com/yoanbernabeu/music-gpt-bundle/actions/workflows/qa.yml/badge.svg)](https://github.com/yoanbernabeu/music-gpt-bundle/actions/workflows/qa.yml)

## Features

- 🎵 **Music AI** - Generate custom music from text prompts
- 🎤 **Cover Songs** - Transform audio with AI voice models
- 🔍 **Conversion Tracking** - Check status and retrieve results
- 🚀 **Type-Safe** - Full PHP 8.4 type safety with DTOs and Enums
- 📦 **Easy to Use** - Simple, intuitive API

## Installation

```bash
composer require yoanbernabeu/music-gpt-bundle
```

If you're using Symfony Flex, the bundle will be automatically registered. Otherwise, add it to your `config/bundles.php`:

```php
return [
    // ...
    YoanBernabeu\MusicGptBundle\MusicGptBundle::class => ['all' => true],
];
```

## Configuration

Create `config/packages/music_gpt.yaml`:

```yaml
music_gpt:
    api:
        api_key: '%env(MUSIC_GPT_API_KEY)%'
```

Add your API key to `.env`:

```env
MUSIC_GPT_API_KEY=your_api_key_here
```

Get your API key at [musicgpt.com/api](https://musicgpt.com/api).

## Usage

### Generate Music with AI

```php
use YoanBernabeu\MusicGptBundle\Contract\MusicAIServiceInterface;
use YoanBernabeu\MusicGptBundle\DTO\MusicAI\MusicAIRequest;

class MusicController
{
    public function __construct(
        private readonly MusicAIServiceInterface $musicAI
    ) {}

    public function generate(): void
    {
        // Simple prompt
        $request = new MusicAIRequest(
            prompt: 'A cheerful song about coding in PHP'
        );

        $response = $this->musicAI->generate($request);

        echo "Task ID: {$response->getTaskId()}\n";
        echo "ETA: {$response->getEta()} seconds\n";
    }

    public function generateAdvanced(): void
    {
        // With custom style and vocals
        $request = new MusicAIRequest(
            musicStyle: 'Lo-fi Hip Hop',
            lyrics: 'Coding all night long...',
            voiceId: 'Drake',
            makeInstrumental: false
        );

        $response = $this->musicAI->generate($request);
        
        // The API generates 2 versions
        [$version1, $version2] = $response->getConversionIds();
    }
}
```

**Available Parameters:**
- `prompt` - Natural language description
- `musicStyle` - Genre (Rock, Pop, Jazz, Lo-fi, etc.)
- `lyrics` - Custom lyrics
- `voiceId` - Voice model for vocals
- `makeInstrumental` - Generate without vocals (default: false)
- `vocalOnly` - Generate vocals only (default: false)
- `webhookUrl` - Callback URL for async notifications

### Create AI Voice Covers

```php
use YoanBernabeu\MusicGptBundle\Contract\CoverServiceInterface;
use YoanBernabeu\MusicGptBundle\DTO\Cover\CoverRequest;

class CoverController
{
    public function __construct(
        private readonly CoverServiceInterface $cover
    ) {}

    public function create(): void
    {
        // From URL
        $request = new CoverRequest(
            audioUrl: 'https://example.com/song.mp3',
            voiceId: 'Drake'
        );

        $response = $this->cover->createCover($request);

        echo "Task ID: {$response->getTaskId()}\n";
        echo "Conversion ID: {$response->getConversionId()}\n";
    }

    public function createWithPitch(): void
    {
        // From local file with pitch adjustment
        $request = new CoverRequest(
            audioFile: '/path/to/audio.wav',
            voiceId: 'Taylor Swift',
            pitch: -2  // -12 to +12 semitones
        );

        $response = $this->cover->createCover($request);
    }
}
```

**Note:** Provide either `audioUrl` OR `audioFile`, not both.

### Track Conversion Status

```php
use YoanBernabeu\MusicGptBundle\Contract\ConversionServiceInterface;
use YoanBernabeu\MusicGptBundle\Enum\ConversionType;

class ConversionController
{
    public function __construct(
        private readonly ConversionServiceInterface $conversion
    ) {}

    public function checkStatus(): void
    {
        // Get details by Task ID
        $details = $this->conversion->getByTaskId(
            taskId: 'task_123456',
            conversionType: ConversionType::MUSIC_AI
        );

        if ($details->isCompleted()) {
            echo "✅ Completed!\n";
            echo "Audio 1: {$details->getAudioUrl1()}\n";
            echo "Audio 2: {$details->getAudioUrl2()}\n";
        } elseif ($details->isProcessing()) {
            echo "⏳ Processing: {$details->getStatus()}\n";
        } elseif ($details->isFailed()) {
            echo "❌ Failed: {$details->getStatusMessage()}\n";
        }
    }

    public function getByConversionId(): void
    {
        // Get details by Conversion ID
        $details = $this->conversion->getByConversionId(
            conversionId: 'conv_789012',
            conversionType: ConversionType::COVER
        );

        if ($details->isCompleted()) {
            echo "Audio: {$details->getAudioUrl()}\n";
            echo "Video: {$details->getVideoUrl()}\n";
            echo "Cover: {$details->getImageUrl()}\n";
        }
    }
}
```

### Complete Workflow Example

```php
use YoanBernabeu\MusicGptBundle\Contract\MusicAIServiceInterface;
use YoanBernabeu\MusicGptBundle\Contract\ConversionServiceInterface;
use YoanBernabeu\MusicGptBundle\DTO\MusicAI\MusicAIRequest;
use YoanBernabeu\MusicGptBundle\Enum\ConversionType;

class WorkflowController
{
    public function __construct(
        private readonly MusicAIServiceInterface $musicAI,
        private readonly ConversionServiceInterface $conversion
    ) {}

    public function generateAndWait(): void
    {
        // 1. Generate music
        $request = new MusicAIRequest(prompt: 'A relaxing piano melody');
        $response = $this->musicAI->generate($request);
        
        $taskId = $response->getTaskId();
        
        // 2. Poll for completion
        $maxAttempts = 30;
        for ($i = 0; $i < $maxAttempts; $i++) {
            sleep(10);
            
            $details = $this->conversion->getByTaskId(
                $taskId,
                ConversionType::MUSIC_AI
            );
            
            if ($details->isCompleted()) {
                echo "✅ Done!\n";
                echo "Download: {$details->getAudioUrl1()}\n";
                break;
            }
            
            if ($details->isFailed()) {
                echo "❌ Failed: {$details->getStatusMessage()}\n";
                break;
            }
            
            echo "⏳ Processing... (attempt {$i}/{$maxAttempts})\n";
        }
    }
}
```

## Error Handling

```php
use YoanBernabeu\MusicGptBundle\Exception\AuthenticationException;
use YoanBernabeu\MusicGptBundle\Exception\PaymentRequiredException;
use YoanBernabeu\MusicGptBundle\Exception\RateLimitException;
use YoanBernabeu\MusicGptBundle\Exception\ValidationException;

try {
    $response = $this->musicAI->generate($request);
} catch (AuthenticationException $e) {
    // Invalid API key (401/403)
    echo "Authentication error: {$e->getMessage()}";
} catch (PaymentRequiredException $e) {
    // Insufficient credits (402)
    echo "Payment required: {$e->getMessage()}";
} catch (RateLimitException $e) {
    // Too many requests (429)
    echo "Rate limited. Retry after: {$e->getRetryAfter()} seconds";
} catch (ValidationException $e) {
    // Invalid parameters (400/422)
    echo "Validation errors: " . print_r($e->getErrors(), true);
}
```

## Rate Limits

Different plans have different limits. When rate limited (429), use `$exception->getRetryAfter()` to know when to retry.

| Plan | Audio Generations | Get by ID |
|------|------------------|-----------|
| Free | 1 parallel | 20/min |
| PLUS | 5 parallel | 200/min |
| PRO  | 10 parallel | 500/min |

See [official rate limits documentation](https://docs.musicgpt.com/api-documentation/utilities/ratelimits).

## API Documentation

- [Music GPT API Docs](https://docs.musicgpt.com)
- [Music AI Endpoint](https://docs.musicgpt.com/api-documentation/conversions/musicai)
- [Cover Endpoint](https://docs.musicgpt.com/api-documentation/conversions/cover)
- [Get by ID Endpoint](https://docs.musicgpt.com/api-documentation/endpoint/getById)

## Development

Run tests:

```bash
composer test
```

Check code style:

```bash
composer cs-check
composer cs-fix
```

Static analysis:

```bash
composer phpstan
```

Run all checks:

```bash
composer cs-check && composer phpstan && composer test
```

## License

This bundle is open-sourced software licensed under the [MIT license](LICENSE).

## Author

**Yoan Bernabeu**
- Website: [yoandev.com](https://yoandev.co)
- GitHub: [@yoanbernabeu](https://github.com/yoanbernabeu)

## Support

For questions or issues, please open an issue on [GitHub](https://github.com/yoanbernabeu/music-gpt-bundle).
