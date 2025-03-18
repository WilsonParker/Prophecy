<?php

namespace Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Str;
use Symfony\Component\DomCrawler\Crawler;
use Tests\TestCase;

class AstroTest extends TestCase
{
    public function test_guzzle_crawl_data_is_correct(): void
    {
        $url = 'https://horoscopes.astro-seek.com/calculate-monthly-astro-calendar/?narozeni_rok=1900&narozeni_mesic=1&custom_calendar=1&kalendar_quick_planeta_1=&kalendar_quick_aspekt=major&kalendar_quick_luna=';
        $client = new Client();
        $res = $client->get($url);

        $crawler = new Crawler($res->getBody());
        $crawler->filter('table')
                ->first()
                ->filter('tr')
                ->slice(2)
                ->first()
                ->each(function (Crawler $node, $i) {
                    $tdList = $node->filter('td');

                    {
                        $td = $tdList->eq(0);
                        $dateNode = $td->filter('strong');
                        $timeNode = $td->filter('.form-info');

                        $this->assertEquals('Jan 1, 1900', $dateNode->text());
                        $date = Carbon::createFromFormat('M j, Y', $dateNode->text());

                        $year = $date->year;
                        $month = $date->month;
                        $day = $date->day;

                        $this->assertEquals(1900, $year);
                        $this->assertEquals(1, $month);
                        $this->assertEquals(1, $day);

                        $this->assertEquals(', 02:56', $timeNode->text());
                        $time = Carbon::createFromFormat('h:i', Str::of($timeNode->text())->remove(', '));

                        $hour = $time->hour;
                        $minute = $time->minute;

                        $this->assertEquals(2, $hour);
                        $this->assertEquals(56, $minute);
                    }

                    {
                        $td = $tdList->eq(1);
                        $description = $td->text();
                        $this->assertEquals('Mercury conjunction Node', $description);
                    }

                    {
                        $td = $tdList->eq(2);
                        $constellation = Str::of($td->filter('img')->attr('alt'))->trim();
                        $location = $td->text();
                        $this->assertEquals('Sagittarius', $constellation);
                        $this->assertEquals('19°09’', $location);
                    }
                });
    }
}
