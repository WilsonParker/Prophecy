<?php

namespace Tests\Unit;

use Carbon\Carbon;
use DateTime;
use Intervention\Zodiac\Calculator;
use PHPUnit\Framework\TestCase;

class ZodiacTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_zodiac_calculator_is_working(): void
    {
        // get zodiac object from a date
        {
            $zodiac = Calculator::zodiac('1980-09-15');
            dump($zodiac);
            $this->assertEquals('virgo', $zodiac->name());
        }

        // method takes mixed formats
        {
            $zodiac = Calculator::zodiac('first day of June 2008');
            dump($zodiac);
        }

        // create from DateTime object
        {
            $zodiac = Calculator::zodiac(new DateTime('1977-03-15'));
            dump($zodiac);
        }

        // get zodiac from a Carbon object
        {
            $zodiac = Calculator::zodiac(Carbon::yesterday());
            dump($zodiac);
        }

        // get zodiac from unix timestamp
        {
            $zodiac = Calculator::zodiac(228268800);
            dump($zodiac);
        }
    }

}
