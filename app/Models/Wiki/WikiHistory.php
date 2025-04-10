<?php

namespace App\Models\Wiki;

use App\Models\BaseModel;
use App\Models\Traits\BelongsToDateTraits;

class WikiHistory extends BaseModel
{
    use BelongsToDateTraits;

    public $table = "wiki_histories";

}
