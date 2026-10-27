<?php

namespace App\Services\Scraper;

use SerpApi\Client;

class ServiceScraper
{
    protected Client $client;

    public function __construct()
    {
        $this->client = new Client(config('services.serpapi.key'));
    }
}
