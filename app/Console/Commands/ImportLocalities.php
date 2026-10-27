<?php

namespace App\Console\Commands;

use App\Services\AI\Enums\ClientProvider;
use App\Services\AI\Factories\LLMClientFactory;
use App\Services\AI\Prompts\LocalitiesPrompt;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

#[Signature('app:import-localities')]
#[Description('Import localities from json and save it into the database')]
class ImportLocalities extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $regions = collect(json_decode(Storage::disk('local')->get('psgc/regions.json'), true))->keyBy('code')->toArray();
        $provinces = collect(json_decode(Storage::disk('local')->get('psgc/provinces.json'), true))->keyBy('code')->toArray();
        $cities = json_decode(Storage::disk('local')->get('psgc/cities.json'), true);
        $municipalities = json_decode(Storage::disk('local')->get('psgc/municipalities.json'), true);

        $localities = [];

        foreach ($cities as $city) {
            $region = $regions[$city['regionCode']]['regionName'] ?? null;
            $province = $provinces[$city['provinceCode']]['name'] ?? null;
            $cityName = $city['name'];

            $address = implode(',', array_filter([$cityName, $province, $region,]));

            $localities[] = ['region' => $region, 'province' => $province, 'city_municipality' => $cityName, 'address' => $address];
        }

        foreach ($municipalities as $municipality) {
            $region = $regions[$municipality['regionCode']]['regionName'] ?? null;
            $province = $provinces[$municipality['provinceCode']]['name'] ?? null;
            $municipalityName = $municipality['name'];

            $address = implode(',', array_filter([$municipalityName, $province, $region]));

            $localities[] = ['region' => $region, 'province' => $province, 'city_municipality' => $municipalityName, 'address' => $address];
        }

        DB::table('localities')->insert($localities);

        return $localities;
    }
}
