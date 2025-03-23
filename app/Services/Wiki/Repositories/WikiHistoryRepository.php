<?php

namespace App\Services\Wiki\Repositories;


use App\Models\Date;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class WikiHistoryRepository extends BaseRepository
{
    public function store(Date $date, string $uri, string $label): Model
    {
        return $this->create([
            'date_id'   => $date->getKey(),
            'event_uri' => $uri,
            'label'     => $label,
        ]);
    }
}