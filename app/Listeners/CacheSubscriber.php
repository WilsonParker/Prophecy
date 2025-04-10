<?php

namespace App\Listeners;

use App\Events\Cache\SetCacheEvent;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

class CacheSubscriber implements ShouldBeUnique
{

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handleSetCacheEvent(SetCacheEvent $event): void
    {
        Redis::set($event->key, $event->data);
    }

    public function subscribe($events)
    {
        $events->listen(
            [SetCacheEvent::class, 'handleSetCacheEvent'],
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
