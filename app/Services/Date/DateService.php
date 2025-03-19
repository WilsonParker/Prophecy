<?php

namespace App\Services\Date;

use App\Models\Date;
use App\Services\Date\Repositories\DateRepository;
use Illuminate\Database\Eloquent\Model;

class DateService
{

    public function __construct(private DateRepository $dateRepository) {}

    public function firstOrFail(int $year, int $month, int $day): Date
    {
        return $this->dateRepository->firstOrFail($year, $month, $day);
    }

    public function create(int $year, int $month, int $day): Model
    {
        return $this->dateRepository->store($year, $month, $day);
    }
}