<?php

namespace App\Services\AI\Factories;

use App\Services\AI\Clients\GeminiClient;
use App\Services\AI\Enums\ClientProvider;
use App\Services\AI\Interface\LLMClient;
use InvalidArgumentException;

class LLMClientFactory
{
    public function make(ClientProvider $provider): LLMClient
    {
        return match ($provider) {
            ClientProvider::GEMINI => app()->make(GeminiClient::class),
            default => throw new InvalidArgumentException(
                "Unsupported LLM provider: {$provider->value}"
            ),
        };
    }
}
