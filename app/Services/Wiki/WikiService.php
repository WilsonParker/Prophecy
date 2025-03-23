<?php

namespace App\Services\Wiki;

use Carbon\Carbon;
use Illuminate\Support\Collection;

class WikiService
{
    public function __construct(private SPARQLQueryDispatcher $dispatcher) {}

    /**
     * @param Carbon $date
     * @param int    $limit
     * @param int    $offset
     * @return \Illuminate\Support\Collection<\App\Services\Wiki\SPARQLQueryItem>
     */
    public function getHistories(Carbon $date, int $limit = 200, int $offset = 0): Collection
    {
        return $this->dispatcher->getHistories($date, $limit, $offset);
    }
}