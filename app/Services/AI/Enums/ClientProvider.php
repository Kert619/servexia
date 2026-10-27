<?php

namespace App\Services\AI\Enums;

enum ClientProvider: string
{
    case GEMINI = 'gemini';
    case OPENAI = 'openai';
    case CLAUDE = 'claude';
}
