<?php

namespace Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Tests\TestCase;

class DateTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_date_format_is_correct(): void
    {
        $date = Carbon::createFromFormat('M j, Y', 'Jan 1, 1900');

        $year = $date->year;
        $month = $date->month;
        $day = $date->day;

        $this->assertEquals(1900, $year);
        $this->assertEquals(1, $month);
        $this->assertEquals(1, $day);

        $time = \Carbon\Carbon::createFromFormat('h:i', Str::of(', 02:56')->remove(', '));

        $hour = $time->hour;
        $minute = $time->minute;

        $this->assertEquals(2, $hour);
        $this->assertEquals(56, $minute);
    }

}
