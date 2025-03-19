<?php

namespace App\Services\Astrology;

use App\Models\Astrology\Astro;
use App\Services\Astrology\Contracts\AstrologyContract;
use App\Services\Astrology\Repositories\AstroRepository;
use App\Services\Date\DateService;
use App\Services\Zodiac\ZodiacService;

class AstrologyService
{

    public function __construct(
        private AstrologyContract $contract,
        private AstroRepository $astroRepository,
        private DateService $dateService,
        private ZodiacService $zodiacService) {}

    /**
     * @param int $year
     * @param int $month
     * @return array<\App\Services\Astrology\AstroData>
     */
    public function getMonthlyAstrology(int $year, int $month): array
    {
        return $this->contract->getMonthlyAstrology($year, $month);
    }

    public function store(AstroData $data): Astro
    {
        $date = $this->dateService->firstOrFail($data->getYear(), $data->getMonth(), $data->getDay());
        $zodiac = $this->zodiacService->firstOrFail($data->getConstellation());
        return $this->astroRepository->store($date, $zodiac, $data);
    }
}