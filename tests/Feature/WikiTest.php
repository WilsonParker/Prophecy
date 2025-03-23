<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\Wiki\WikiService;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class WikiTest extends TestCase
{
    public function test_wiki_data_is_correct(): void
    {
        $service = app(WikiService::class);
        $date = Carbon::create(2025, 1, 1);
        $result = $service->getHistories($date, 10, 0);

        $first = $result->first();

        $this->assertEquals('uri', $first->event['type']);
        $this->assertEquals('http://www.wikidata.org/entity/Q130308722', $first->event['value']);
        $this->assertEquals('http://www.w3.org/2001/XMLSchema#dateTime', $first->date['datatype']);
        $this->assertEquals('literal', $first->date['type']);
        $this->assertEquals('2025-01-01T00:00:00Z', $first->date['value']);
        $this->assertEquals('en', $first->eventLabel['xml:lang']);
        $this->assertEquals('literal', $first->eventLabel['type']);
        $this->assertEquals('renovation of Carolabrücke', $first->eventLabel['value']);
    }


}
