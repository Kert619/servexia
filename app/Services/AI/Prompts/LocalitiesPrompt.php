<?php

namespace App\Services\AI\Prompts;

class LocalitiesPrompt
{
    public static function make(string $cityMunicipality, string $region, ?string $province)
    {
        $prompt = <<<PROMPT
        You are generating accurate geographic reference data for Philippine cities and municipalities.

        You MUST use Google Search to verify the location before generating the response.

        SEARCH INSTRUCTIONS:

        1. Perform a Google Search using this exact search query format:

        "$cityMunicipality, $province, $region latitude and longitude"

        2. Example:

        "City of Escalante, Negros Occidental, Region VI latitude and longitude"

        3. Inspect the Google Search results for the AI Overview / AI-generated overview.

        4. If a Google AI Overview is displayed, extract the latitude and longitude specifically from the information presented in the AI Overview.

        5. Use the coordinates stated or provided by the Google AI Overview as the latitude and longitude values.

        OUTPUT INSTRUCTIONS:
        - Return JSON only.
        - DO NOT RETURN A MARKDOWN CODE BLOCK.
        - DO NOT RETURN `json or `.
        - DO NOT RETURN ANY EXPLANATION, COMMENTARY, OR ADDITIONAL TEXT.
        - DO NOT RETURN A NESTED ARRAY.
        - USE EXACTLY THIS JSON STRUCTURE:

        {
            "locality": "City of Laoag",
            "province": "Ilocos Norte",
            "region": "Region I",
            "latitude": "18.19892",
            "longitude": "120.59373"
        }

        INPUT:

        Location:
        $cityMunicipality

        Province:
        $province

        Region:
        $region

        PROMPT;

        return $prompt;
    }
}
