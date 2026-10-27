<?php

namespace App\Services\AI\Clients;

use App\Services\AI\Interface\LLMClient;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

class GeminiClient implements LLMClient
{
    protected string $apiKey;
    protected string $apiUrl;
    protected Client $httpClient;
    private array $response;

    public function __construct()
    {
        $this->apiKey = config('services.gemini.key');

        $this->apiUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-3.1-flash-lite:generateContent';

        $this->httpClient = new Client([
            'timeout' => 30,
        ]);
    }

    /**
     * Generate a response from Gemini.
     */
    public function prompt(string $prompt): static
    {
        try {
            $response = $this->httpClient->post($this->apiUrl, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'x-goog-api-key' => $this->apiKey,
                ],
                'json' => [
                    'contents' => [
                        [
                            'parts' => [
                                [
                                    'text' => $prompt,
                                ],
                            ],
                        ],
                    ],
                ],
            ]);

            $this->response = json_decode(
                $response->getBody()->getContents(),
                true
            );

            return $this;
        } catch (GuzzleException $e) {
            throw new Exception(
                'Error communicating with Gemini API: ' . $e->getMessage(),
                previous: $e
            );
        }
    }

    public function json(): array
    {

        $decoded = json_decode($this->extractText(), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error($this->response);
            throw new Exception('LLM did not return valid JSON');
        }

        return $decoded;
    }

    public function text(): string
    {
        return $this->extractText();
    }

    public function image(): string
    {
        throw new \Exception('Not implemented');
    }

    private function extractText()
    {
        $text = $this->response['candidates'][0]['content']['parts'][0]['text'] ?? null;

        if (!$text) {
            throw new Exception('Gemini returned no response text.');
        }

        return $text;
    }
}
