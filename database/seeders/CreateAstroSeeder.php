<?php

namespace Database\Seeders;

use App\Services\Astrology\AstrologyService;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class CreateAstroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $astroService = app()->make(AstrologyService::class);

        $start = Carbon::createFromFormat('Y-m', '1900-01');
        $end = Carbon::createFromFormat('Y-m', '2100-12');

        $date = $start;
        do {
            collect($astroService->getMonthlyAstrology($date->year, $date->month))
                ->each(function ($astroData) use ($astroService) {
                    $astroService->store($astroData);
                });
        } while ($date->lessThanOrEqualTo($end) && $date->addMonth());
    }
}