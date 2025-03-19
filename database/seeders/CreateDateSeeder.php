<?php

namespace Database\Seeders;

use App\Services\Date\DateService;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class CreateDateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $service = app()->make(DateService::class);
        $date = Carbon::parse('1900-01-01');
        $end = Carbon::parse('2100-12-31');

        do {
            $service->create($date->year, $date->month, $date->day);
        } while ($date->lessThanOrEqualTo($end) && $date->addDay());
    }
}
