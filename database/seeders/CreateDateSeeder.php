<?php

namespace Database\Seeders;

use App\Models\Date;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class CreateDateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $date = Carbon::parse('1900-01-01');
        $end = Carbon::parse('2100-12-31');

        do {
            Date::create([
                'year'  => $date->year,
                'month' => $date->month,
                'day'   => $date->day,
            ]);
        } while ($date->lessThanOrEqualTo($end) && $date->addDay());
    }
}
