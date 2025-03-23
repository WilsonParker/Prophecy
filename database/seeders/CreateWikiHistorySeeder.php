<?php

namespace Database\Seeders;

use App\Services\Date\DateService;
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
        $dateService = app()->make(DateService::class);

        // $start = Carbon::createFromFormat('Y-m-d', '1900-01-01');
        // $end = Carbon::createFromFormat('Y-m-d', '2100-12-31');

        $start = Carbon::createFromFormat('Y-m-d', '1900-01-01');
        $end = Carbon::createFromFormat('Y-m-d', '2100-12-31');

        $date = $start;
        $limit = 400;
        $offset = 0;
        do {
            $dateModel = $dateService->firstOrFail($date->year, $date->month, $date->day);
            dump("{$dateModel->getKey()}, $dateModel->year-$dateModel->month-$dateModel->day");
            //            do {
            dump("offset: $offset");
            $result = collect($wikiService->getHistories($date, $limit, $offset));
            dump("result count : {$result->count()}");
            $result->each(fn($item) => $wikiService->storeWithItem($dateModel, $item));
            $offset++;
            //            } while ($result->count() >= $limit);
            $offset = 0;
        } while ($date->lessThanOrEqualTo($end) && $date->addDay());
    }
}