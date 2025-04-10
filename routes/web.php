<?php

use App\Services\Wiki\WikiHistoryService;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function () {
    $service = app()->make(WikiHistoryService::class);
    $result = $service->search('Summer Olympics');
    dump($result);
});

Route::get('/remove', function () {
    // \Illuminate\Support\Facades\Redis::del('wiki_history:search:world');
    \Illuminate\Support\Facades\Redis::del('wiki_history:search:Summer Olympics');
});
