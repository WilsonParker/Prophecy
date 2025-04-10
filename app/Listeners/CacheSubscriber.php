<?php

namespace App\Listeners;

use App\Events\Cache\SetWikiHistoryCacheEvent;
use App\Services\Wiki\Repositories\WikiHistoryRepository;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

class CacheSubscriber implements ShouldQueue, ShouldBeUnique
{
    use Queueable;

    //    public $connection = 'redis';
    //    public $queue = 'cache';
    //    public $delay = 10;

    /**
     * Create the event listener.
     */
    public function __construct(private WikiHistoryRepository $repository)
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handleSetCacheEvent(SetWikiHistoryCacheEvent $event): void
    {
        Redis::set($event->key, $this->repository->search($event->key));
    }

    public function subscribe($events)
    {
        $events->listen(
            [SetWikiHistoryCacheEvent::class, 'handleSetCacheEvent'],
        );
    }

    /**
     * Get the cache driver for the unique job lock.
     */
    public function uniqueVia(): Repository
    {
        return Cache::driver('redis');
    }
}
