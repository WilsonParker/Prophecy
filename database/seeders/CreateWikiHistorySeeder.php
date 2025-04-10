<?php

namespace Database\Seeders;

use App\Services\Wiki\WikiService;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class CreateWikiHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $wikiService = app()->make(WikiService::class);

        $start = Carbon::createFromFormat('Y', '1900');
        $end = Carbon::createFromFormat('Y', 's2100');

        $date = $start;

        do {
            dump($date->format('Y'));
            $result = collect($wikiService->getHistoriesPerYear($date->year));
            dump("result count : {$result->count()}");
            $result->each(fn($item) => $wikiService->storeWithItem($item));
        } while ($date->lessThanOrEqualTo($end) && $date->addYear());
    }
}