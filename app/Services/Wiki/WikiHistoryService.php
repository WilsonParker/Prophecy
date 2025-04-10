<?php

namespace App\Services\Wiki;

use App\Events\Cache\SetWikiHistoryCacheEvent;
use App\Services\Wiki\Repositories\WikiHistoryRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Redis;

class WikiHistoryService
{
    public function __construct(private WikiHistoryRepository $wikiHistoryRepository) {}

    /**
     * @param string $keyword
     * @return \Illuminate\Support\Collection<\App\Services\Wiki\SPARQLQueryItem>
     */
    public function search(string $keyword): Collection
    {
        $cacheKey = 'wiki_history:search:' . $keyword;

        if ($cached = Redis::get($cacheKey)) {
            return collect(json_decode($cached, true));
        }

        SetWikiHistoryCacheEvent::dispatch($cacheKey);

        return $this->wikiHistoryRepository->search($keyword);
    }

}