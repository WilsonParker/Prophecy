<?php

namespace App\Services\Astrology;

use Illuminate\Support\Str;

class AstroData
{
    public string $location;
    private int $year;
    private int $month;
    private int $day;
    private int $hour;
    private int $minute;
    private string $description;
    private string $constellation {
        set (string $value) {
            $this->constellation = Str::of($value)->lower()->toString();
        }
    }

    public function getYear(): int
    {
        return $this->year;
    }

    public function setYear(int $year): self
    {
        $this->year = $year;
        return $this;
    }

    public function getMonth(): int
    {
        return $this->month;
    }

    public function setMonth(int $month): self
    {
        $this->month = $month;
        return $this;
    }

    public function getDay(): int
    {
        return $this->day;
    }

    public function setDay(int $day): self
    {
        $this->day = $day;
        return $this;
    }

    public function getHour(): int
    {
        return $this->hour;
    }

    public function setHour(int $hour): self
    {
        $this->hour = $hour;
        return $this;
    }

    public function getMinute(): int
    {
        return $this->minute;
    }

    public function setMinute(int $minute): self
    {
        $this->minute = $minute;
        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getConstellation(): string
    {
        return $this->constellation;
    }

    public function setConstellation(string $constellation): self
    {
        $this->constellation = Str::of($constellation)->lower()->toString();
        return $this;
    }

    public function getLocation(): string
    {
        return $this->location;
    }

    public function setLocation(string $location): self
    {
        $this->location = $location;
        return $this;
    }

}