<?php

namespace App\Services\Astrology\Repositories;


use App\Models\Date;
use App\Models\Zodiac\Zodiac;
use App\Repositories\BaseRepository;
use App\Services\Astrology\AstroData;
use Illuminate\Database\Eloquent\Model;

class AstroRepository extends BaseRepository
{
    public function store(Date $date, Zodiac $zodiac, AstroData $data): Model
    {
        return $this->create([
            'date_id'     => $date->getKey(),
            'zodiac_code' => $zodiac->getKey(),
            'hour'        => $data->getHour(),
            'minute'      => $data->getMinute(),
            'description' => $data->getDescription(),
            'motion'      => $data->getMonth(),
        ]);
    }
}