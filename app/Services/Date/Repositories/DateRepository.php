<?php

namespace App\Services\Date\Repositories;

use App\Models\Date;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class DateRepository extends BaseRepository
{
    public function firstOrFail(int $year, int $month, int $day): Date
    {
        return $this->getQuery()
                    ->where('year', $year)
                    ->where('month', $month)
                    ->where('day', $day)
                    ->firstOrFail();
    }

    public function store(int $year, int $month, int $day): Model
    {
        return $this->create([
            'year'  => $year,
            'month' => $month,
            'day'   => $day,
        ]);
    }
}