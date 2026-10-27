<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use SerpApi\Client;
use SerpApi\SerpApiException;

class SerpApi
{
    protected Client $client;
    const MAX_DISTANCE = 20;

    public function __construct()
    {
        $this->client = new Client(config('services.serpapi.key'));
    }

    /**
     * Search Google Maps local results via SerpApi.
     *
     * @param string $query The search term to look up, e.g. "aircon service", "plumbing service".
     *                       Pass the `search_query_for_scraping` value from your categories table.
     * @param string $lat    Latitude, e.g. "9.32244060" — pulled from your geocoded localities table.
     * @param string $lon    Longitude, e.g. "123.29596410" — pulled from your geocoded localities table.
     * @param int    $m      Search radius in meters. Defaults to 20000 (~20km).
     *                        Higher = wider area, lower = tighter.
     */
    public function search(string $query, string $lat, string $lon, int $m = 20000): array
    {
        $ll = "@{$lat},{$lon},{$m}m";

        $query = [
            'engine' => 'google_maps',
            'q' => $query,
            'll' => $ll,
            'hl' => 'en',
            'gl' => 'ph'
        ];

        $allResults = [];
        $start = 0;
        $maxStart = 100; // SerpApi's documented safe ceiling — results duplicate/become irrelevant beyond this

        while ($start <= $maxStart) {
            if ($start > 0) {
                $query['start'] = $start;
            }

            try {
                $result = $this->client->search($query);
            } catch (SerpApiException $e) {
                // "Google hasn't returned any results for this query" is not a
                // real failure — it just means this query/location has nothing.
                if (str_contains($e->getMessage(), "hasn't returned any results")) {
                    break;
                }

                throw $e;
            }

            $localResults = $result->local_results ?? [];
            $allResults = array_merge($allResults, (array) $localResults);

            if (empty($result->serpapi_pagination->next ?? null)) {
                break; // no more pages for this query/location
            }

            $start += 20;
            sleep(2); // throttle between paginated requests
        }

        /*
         * Now perform our own strict 20 km filtering.
         */
        return $this->filterDistance(
            $allResults,
            (float) $lat,
            (float) $lon,
            self::MAX_DISTANCE
        );
    }

    /**
     * Keep only businesses within the specified radius.
     */
    private function filterDistance(
        array $results,
        float $lat,
        float $lon,
        float $radiusKm
    ): array {
        $filteredResults = [];

        foreach ($results as $business) {

            $businessLat = $business->gps_coordinates->latitude ?? null;
            $businessLon = $business->gps_coordinates->longitude ?? null;

            // Skip results without GPS coordinates.
            if ($businessLat === null || $businessLon === null) {
                continue;
            }

            $distance = $this->calculateDistanceKm(
                $lat,
                $lon,
                (float) $businessLat,
                (float) $businessLon
            );

            /*
             * Only keep businesses within the radius.
             */
            if ($distance <= $radiusKm) {
                $filteredResults[] = $business;
            }
        }

        return $filteredResults;
    }

    /**
     * Calculate the straight-line distance between
     * two GPS coordinates using the Haversine formula.
     *
     * @return float Distance in kilometers.
     */
    private function calculateDistanceKm(
        float $lat1,
        float $lon1,
        float $lat2,
        float $lon2
    ): float {
        $earthRadius = 6371;

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a =
            sin($dLat / 2) ** 2 +
            cos(deg2rad($lat1)) *
            cos(deg2rad($lat2)) *
            sin($dLon / 2) ** 2;

        $c = 2 * atan2(
            sqrt($a),
            sqrt(1 - $a)
        );

        return $earthRadius * $c;
    }
}
