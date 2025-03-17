<?php

namespace Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;
use Tests\TestCase;

class AstroTest extends TestCase
{
    public function test_guzzle_crawl_data_is_correct(): void
    {
        $url = 'https://horoscopes.astro-seek.com/calculate-monthly-astro-calendar/?narozeni_rok=1900&narozeni_mesic=1&custom_calendar=1&kalendar_quick_planeta_1=&kalendar_quick_aspekt=major&kalendar_quick_luna=';
        $client = new Client();
        $res = $client->get($url);
        $res = $res->getBody();
        $html = (string)$res;

        $crawler = new Crawler($html);
        $crawler->filter('table')
                ->first()
                ->filter('tR')
                ->slice(2)
                ->first()
                ->each(function (Crawler $node, $i) {
                    $tdList = $node->filter('td');

                    {
                        $td = $tdList->eq(0);
                        $date = $td->filter('strong');
                        $time = $td->filter('.form-info');

                        $this->assertEquals('Jan 1, 1900', $date->text());
                        $this->assertEquals(', 02:56', $time->text());
                    }

                    {
                        $td = $tdList->eq(1);
                        $this->assertEquals('Mercury conjunction Node', $td->text());
                    }

                    {
                        $td = $tdList->eq(2);
                        $this->assertEquals('19°09’', $td->text());
                    }
                });
    }
}
