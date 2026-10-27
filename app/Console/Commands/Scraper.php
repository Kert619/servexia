<?php

namespace App\Console\Commands;

use App\Services\SerpApi;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

#[Signature('app:scrape')]
#[Description('Run the scraper')]
class Scraper extends Command
{
    public function handle(SerpApi $serpApi)
    {

        $localities = $this->getLocalities();
        $categories = $this->getCategories();

        foreach ($localities as $locality) {
            $latitude = $locality->latitude;
            $longitude = $locality->longitude;

            foreach ($categories as $category) {
                if (!$this->shouldScrape(
                    $locality->locality_id,
                    $category->category_id
                )) {
                    $this->info(
                        "Skipping {$category->category_name} for {$locality->address}"
                    );

                    continue;
                }

                $results = $serpApi->search($category->search_query_for_scraping, $latitude, $longitude);

                if (empty($results)) {
                    $this->warn("No results found for {$category->category_name} in {$locality->address}");
                    continue;
                }

                $this->saveToDatabase($results);

                $this->updateScrapeLogs($locality->locality_id, $category->category_id);

                $this->line("Finished scraping {$category->category_name} for {$locality->address}");

                sleep(1);
            }
        }
    }

    private function getCategories()
    {
        return DB::select("SELECT 
        c.category_id,
        c.category_name,
        c.parent_category_id,
        c.search_query_for_scraping
        FROM categories c
        LEFT JOIN categories child
        ON child.parent_category_id = c.category_id
        WHERE child.category_id IS NULL");
    }

    private function getLocalities()
    {
        return DB::select("SELECT * FROM localities WHERE province = 'Negros Oriental'");
    }

    private function saveToDatabase(array $results)
    {
        foreach ($results as $result) {
            DB::table('services')->upsert(
                [
                    'place_id' => $result->place_id,
                    'service_name' => $result->title,
                    'latitude' => $result->gps_coordinates->latitude,
                    'longitude' => $result->gps_coordinates->longitude,
                    'rating' => $result->rating ?? null,
                    'reviews' => $result->reviews ?? null,
                    'unclaimed_listing' => $result->unclaimed_listing ?? null,
                    'address' => $result->address ?? null,
                    'phone' => $result->phone ?? null,
                    'website' => $result->website ?? null,
                    'reviews_link' => $result->reviews_link ?? null,
                    'photos_link' => $result->photos_link ?? null,
                    'type' => $result->type ?? null,
                    'types' => isset($result->types)
                        ? json_encode($result->types)
                        : null,
                    'type_id' => $result->type_id ?? null,
                    'type_ids' => isset($result->type_ids)
                        ? json_encode($result->type_ids)
                        : null,
                    'user_review' => $result->user_review ?? null,
                    'operating_hours' => isset($result->operating_hours)
                        ? json_encode($result->operating_hours)
                        : null,
                    'thumbnail' => $result->thumbnail ?? null,
                ],
                ['place_id'],
                [
                    'service_name',
                    'latitude',
                    'longitude',
                    'rating',
                    'reviews',
                    'unclaimed_listing',
                    'address',
                    'phone',
                    'website',
                    'reviews_link',
                    'photos_link',
                    'type',
                    'types',
                    'type_id',
                    'type_ids',
                    'user_review',
                    'operating_hours',
                    'thumbnail',
                ]
            );
        }
    }

    private function updateScrapeLogs(int $localityId, int $categoryId)
    {
        DB::table('scrape_logs')->upsert(
            [
                'locality_id' => $localityId,
                'category_id' => $categoryId,
                'next_scrape' => now()->addMonths(6)
            ],
            ['locality_id', 'category_id'],
            ['next_scrape']
        );
    }

    private function shouldScrape(int $localityId, int $categoryId): bool
    {
        $scrapeLog = DB::table('scrape_logs')
            ->where('locality_id', $localityId)
            ->where('category_id', $categoryId)
            ->first();

        // No previous scrape or next_scrape is NULL
        if (!$scrapeLog || $scrapeLog->next_scrape === null) {
            return true;
        }

        // Scrape only when next_scrape has been reached
        return now()->gte($scrapeLog->next_scrape);
    }
}
