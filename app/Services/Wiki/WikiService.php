<?php

namespace App\Services\Wiki;

use App\Models\Date;
use App\Services\Wiki\Repositories\WikiHistoryRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class WikiService
{
    public function __construct(private SPARQLQueryDispatcher $dispatcher, private WikiHistoryRepository $wikiHistoryRepository) {}

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

    public function storeWithItem(Date $date, SPARQLQueryItem $item): Model
    {
        return $this->wikiHistoryRepository->store($date, $item->event['value'], $item->eventLabel['value']);
    }

    public function store(Date $date, string $uri, string $label): Model
    {
        return $this->wikiHistoryRepository->store($date, $uri, $label);
    }
}