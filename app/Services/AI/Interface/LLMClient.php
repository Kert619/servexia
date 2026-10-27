<?php

namespace App\Services\AI\Interface;

interface LLMClient
{
    public function prompt(string $prompt): static;

    public function json(): array;

    public function text(): string;

    public function image(): string;
}
