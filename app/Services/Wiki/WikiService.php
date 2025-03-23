<?php

namespace App\Services\Wiki;

use App\Models\Date;
use App\Services\Date\DateService;
use App\Services\Wiki\Repositories\WikiHistoryRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class WikiService
{
    public function __construct(private SPARQLQueryDispatcher $dispatcher, private WikiHistoryRepository $wikiHistoryRepository, private DateService $dateService) {}

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

    /**
     * @param int $year
     * @param int $month
     * @return \Illuminate\Support\Collection<\App\Services\Wiki\SPARQLQueryItem>
     */
    public function getHistoriesPerMonth(int $year, int $month): Collection
    {
        return $this->dispatcher->getHistoriesPerMonth($year, $month);
    }

    /**
     * @param int $year
     * @return \Illuminate\Support\Collection<\App\Services\Wiki\SPARQLQueryItem>
     */
    public function getHistoriesPerYear(int $year): Collection
    {
        return $this->dispatcher->getHistoriesPerYear($year);
    }

    public function storeWithItem(SPARQLQueryItem $item): Model
    {
        $date = Carbon::createFromFormat('Y-m-d\TH:i:s\Z', $item->date['value']);
        $dateModel = $this->dateService->firstOrFailWithDate($date);
        return $this->wikiHistoryRepository->store($dateModel, $item->event['value'], $item->eventLabel['value']);
    }

    public function store(Date $date, string $uri, string $label): Model
    {
        return $this->wikiHistoryRepository->store($date, $uri, $label);
    }
}