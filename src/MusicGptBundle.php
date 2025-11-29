<?php

declare(strict_types=1);

namespace YoanBernabeu\MusicGptBundle;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

use Symfony\Component\HttpKernel\Bundle\AbstractBundle;
use YoanBernabeu\MusicGptBundle\Contract\ConversionServiceInterface;
use YoanBernabeu\MusicGptBundle\Contract\CoverServiceInterface;
use YoanBernabeu\MusicGptBundle\Contract\ExtractionServiceInterface;
use YoanBernabeu\MusicGptBundle\Contract\MusicAIServiceInterface;
use YoanBernabeu\MusicGptBundle\Contract\TextToSpeechServiceInterface;
use YoanBernabeu\MusicGptBundle\Contract\VoiceServiceInterface;
use YoanBernabeu\MusicGptBundle\Service\ConversionService;
use YoanBernabeu\MusicGptBundle\Service\CoverService;
use YoanBernabeu\MusicGptBundle\Service\ExtractionService;
use YoanBernabeu\MusicGptBundle\Service\HttpClient;
use YoanBernabeu\MusicGptBundle\Service\MusicAIService;
use YoanBernabeu\MusicGptBundle\Service\TextToSpeechService;
use YoanBernabeu\MusicGptBundle\Service\VoiceService;

/**
 * Music GPT Bundle.
 *
 * Symfony bundle for integrating Music GPT API into your applications.
 */
class MusicGptBundle extends AbstractBundle
{
    /**
     * Configure the bundle's configuration tree.
     */
    public function configure(DefinitionConfigurator $definition): void
    {
        /** @var ArrayNodeDefinition $rootNode */
        $rootNode = $definition->rootNode();

        $rootNode
            ->children()
                ->arrayNode('api')
                    ->isRequired()
                    ->children()
                        ->scalarNode('base_url')
                            ->defaultValue('https://api.musicgpt.com/api/public/v1')
                            ->cannotBeEmpty()
                            ->info('Base URL of the Music GPT API')
                        ->end()
                        ->scalarNode('api_key')
                            ->isRequired()
                            ->cannotBeEmpty()
                            ->info('API key for authentication (get yours at https://musicgpt.com)')
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    /**
     * Load the bundle's services and configuration.
     *
     * @param array<string, mixed> $config
     */
    public function loadExtension(
        array $config,
        ContainerConfigurator $container,
        ContainerBuilder $builder
    ): void {
        // Register parameters
        $container->parameters()
            ->set('yoanbernabeu_music_gpt.api.base_url', $config['api']['base_url'])
            ->set('yoanbernabeu_music_gpt.api.api_key', $config['api']['api_key'])
        ;

        // Register services
        $container->services()
            // Internal HTTP client (not meant to be used directly)
            ->set(HttpClient::class)
                ->args([
                    service('http_client'),
                    '%yoanbernabeu_music_gpt.api.base_url%',
                    '%yoanbernabeu_music_gpt.api.api_key%',
                ])
                ->private()

            // Music AI Service
            ->set(MusicAIService::class)
                ->args([service(HttpClient::class)])
                ->public()
            ->alias(MusicAIServiceInterface::class, MusicAIService::class)
                ->public()
            ->alias('yoanbernabeu_music_gpt.music_ai', MusicAIServiceInterface::class)
                ->public()

            // Cover Service
            ->set(CoverService::class)
                ->args([service(HttpClient::class)])
                ->public()
            ->alias(CoverServiceInterface::class, CoverService::class)
                ->public()
            ->alias('yoanbernabeu_music_gpt.cover', CoverServiceInterface::class)
                ->public()

            // Text To Speech Service
            ->set(TextToSpeechService::class)
                ->args([service(HttpClient::class)])
                ->public()
            ->alias(TextToSpeechServiceInterface::class, TextToSpeechService::class)
                ->public()
            ->alias('yoanbernabeu_music_gpt.text_to_speech', TextToSpeechServiceInterface::class)
                ->public()

            // Extraction Service
            ->set(ExtractionService::class)
                ->args([service(HttpClient::class)])
                ->public()
            ->alias(ExtractionServiceInterface::class, ExtractionService::class)
                ->public()
            ->alias('yoanbernabeu_music_gpt.extraction', ExtractionServiceInterface::class)
                ->public()

            // Voice Service (Helper for voice search and listing)
            ->set(VoiceService::class)
                ->args([service(HttpClient::class)])
                ->public()
            ->alias(VoiceServiceInterface::class, VoiceService::class)
                ->public()
            ->alias('yoanbernabeu_music_gpt.voice', VoiceServiceInterface::class)
                ->public()

            // Conversion Service (Helper for retrieving conversion details)
            ->set(ConversionService::class)
                ->args([service(HttpClient::class)])
                ->public()
            ->alias(ConversionServiceInterface::class, ConversionService::class)
                ->public()
            ->alias('yoanbernabeu_music_gpt.conversion', ConversionServiceInterface::class)
                ->public()
        ;
    }

    /**
     * Get the bundle's root directory path.
     */
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
