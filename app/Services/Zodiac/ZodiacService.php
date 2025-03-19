<?php

namespace App\Services\Zodiac;

use App\Models\Zodiac\Zodiac;
use App\Services\Zodiac\Repositories\ZodiacRepository;

class ZodiacService
{

    public function __construct(private ZodiacRepository $zodiacRepository) {}

    public function firstOrFail(string $code): Zodiac
    {
        return $this->zodiacRepository->firstOrFail($code);
    }

}