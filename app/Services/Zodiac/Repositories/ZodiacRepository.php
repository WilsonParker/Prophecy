<?php

namespace App\Services\Zodiac\Repositories;

use App\Models\Zodiac\Zodiac;
use App\Repositories\BaseRepository;

class ZodiacRepository extends BaseRepository
{
    public function firstOrFail(string $code): Zodiac
    {
        return $this->getQuery()->findOrFail($code);
    }
}