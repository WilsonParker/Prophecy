<?php

namespace App\Services\Wiki;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class SPARQLQueryDispatcher
{
    private string $endpointUrl = 'https://query.wikidata.org/sparql';
    private string $sparqlQueryString = <<< 'SPARQL'
SELECT ?event ?eventLabel ?date
WITH {
  SELECT DISTINCT ?event ?date
  WHERE {
    # Find events
    ?event wdt:P31/wdt:P279* wd:Q1190554.
    # Events with a point in time or start date
    OPTIONAL { ?event wdt:P585 ?date. }
    OPTIONAL { ?event wdt:P580 ?date. }
    # Ensure at least one date is available
    FILTER(BOUND(?date) && DATATYPE(?date) = xsd:dateTime).
    # Filter events in January 2013
    # FILTER("2013-01-01T00:00:00Z"^^xsd:dateTime <= ?date && ?date < "2013-02-01T23:59:59Z"^^xsd:dateTime).
    FILTER("$dateT00:00:00Z"^^xsd:dateTime <= ?date && ?date < "$dateT23:59:59Z"^^xsd:dateTime).
  }
  LIMIT $limit
  # 다음 요청에서는 OFFSET 200, 400 등으로 변경
  OFFSET $offset
} AS %i
WHERE {
  INCLUDE %i
  SERVICE wikibase:label { bd:serviceParam wikibase:language "[AUTO_LANGUAGE],mul,en" . }
}
  # ORDER BY ?dateTime
SPARQL;

    /**
     * @param Carbon $date
     * @param int    $limit
     * @param int    $offset
     * @return \Illuminate\Support\Collection<\App\Services\Wiki\SPARQLQueryItem>
     */
    public function getHistories(Carbon $date, int $limit = 200, int $offset = 0): Collection
    {
        $queryResult = $this->query(Str::of($this->sparqlQueryString)
                                       ->replace('$date', $date->format('Y-m-d'))
                                       ->replace('$limit', $limit)
                                       ->replace('$offset', $offset));

        return collect($queryResult['results']['bindings'])->map(function ($item) {
            $sparqlQueryItem = new SPARQLQueryItem();
            $sparqlQueryItem->bind($item);
            return $sparqlQueryItem;
        });
    }

    private function query(string $sparqlQuery): array
    {
        $opts = [
            'http' => [
                'method' => 'GET',
                'header' => [
                    'Accept: application/sparql-results+json',
                    'User-Agent: WDQS-example PHP/' . PHP_VERSION, // TODO adjust this; see https://w.wiki/CX6
                ],
            ],
        ];
        $context = stream_context_create($opts);

        $url = $this->endpointUrl . '?query=' . urlencode($sparqlQuery);
        $response = file_get_contents($url, false, $context);
        return json_decode($response, true);
    }

}