<?php

namespace Database\Seeders;

use App\Models\Zodiac\Zodiac;
use Illuminate\Database\Seeder;

class CreateZodiacSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $zodiacs = [
            'aries'       => '양자리 3-21 ~ 4-19',
            'taurus'      => '황소자리 4-20 ~ 5-20',
            'gemini'      => '쌍둥이자리 5-21 ~ 6-21',
            'cancer'      => '게자리 6-22 ~ 7-22',
            'leo'         => '사자자리 7-23 ~ 8-22',
            'virgo'       => '처녀자리 8-23 ~ 9-22',
            'libra'       => '천칭자리 9-23 ~ 10-23',
            'scorpio'     => '전갈자리 10-24 ~ 11-22',
            'sagittarius' => '궁수자리 11-23 ~ 12-21',
            'capricorn'   => '염소자리 12-22 ~ 1-19',
            'aquarius'    => '물병자리 1-20 ~ 2-18',
            'pisces'      => '물고기자리 2-19 ~ 3-20',
        ];

        foreach ($zodiacs as $name => $description) {
            Zodiac::create([
                'code'        => $name,
                'description' => $description,
            ]);
        }
    }
}
