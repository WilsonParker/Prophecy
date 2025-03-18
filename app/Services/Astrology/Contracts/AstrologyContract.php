<?php

namespace App\Services\Astrology\Contracts;

interface AstrologyContract
{

    /**
     * @param int $year
     * @param int $month
     * @return array<\App\Services\Astrology\AstroData>
     */
    public function getMonthlyAstrology(int $year, int $month): array;
}