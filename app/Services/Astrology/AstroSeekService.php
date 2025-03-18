<?php

namespace App\Services\Astrology;

use App\Services\Astrology\Contracts\AstrologyContract;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Str;
use Symfony\Component\DomCrawler\Crawler;

class AstroSeekService implements AstrologyContract
{
    private string $url = 'https://horoscopes.astro-seek.com/calculate-monthly-astro-calendar/?narozeni_rok=$year&narozeni_mesic=$month&custom_calendar=1&kalendar_quick_planeta_1=&kalendar_quick_aspekt=major&kalendar_quick_luna=';
    private Client $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * @param int $year
     * @param int $month
     * @return array<\App\Services\Astrology\AstroData>
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getMonthlyAstrology(int $year, int $month): array
    {
        $result = [];
        $response = $this->client->get(Str::of($this->url)->replace('$year', $year)->replace('$month', $month)->toString());
        $crawler = new Crawler($response->getBody());
        $trList = $crawler->filter('table')
                          ->first()
                          ->filter('tr');
        $trList->slice(2, $trList->count() - 3)
               ->each(function (Crawler $node, $i) use (&$result) {
                   $tdList = $node->filter('td');

                   $td = $tdList->eq(0);
                   $dateNode = $td->filter('strong');
                   $timeNode = $td->filter('.form-info');

                   $date = Carbon::createFromFormat('M j, Y', $dateNode->text());
                   $time = Carbon::createFromFormat('H:i', Str::of($timeNode->text())->remove(', '));

                   $td1 = $tdList->eq(1);
                   $td2 = $tdList->eq(2);
                   $constellation = Str::of($td2->filter('img')->attr('alt'))->trim();

                   $astro = new AstroData();
                   $astro->setYear($date->year)
                         ->setMonth($date->month)
                         ->setDay($date->day)
                         ->setHour($time->hour)
                         ->setMinute($time->minute)
                         ->setDescription($td1->text())
                         ->setConstellation($constellation)
                         ->setLocation($td2->text());
                   $result[] = $astro;
               });
        return $result;
    }
}