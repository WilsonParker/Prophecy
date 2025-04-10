<?php

namespace App\Models\Traits;

use App\Models\Date;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToDateTraits
{

    public function date(): BelongsTo
    {
        return $this->belongsTo(Date::class, 'date_id');
    }
}