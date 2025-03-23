<?php

namespace App\Services\Wiki;

use LaravelSupports\Objects\Traits\Bindable;

class SPARQLQueryItem
{

    use Bindable;

    /*
     * "type" => "uri"
     * "value" => "http://www.wikidata.org/entity/Q16954943"
     * */
    public array $event;

    /*
     * "datatype" => "http://www.w3.org/2001/XMLSchema#dateTime"
     * "type" => "literal"
     * "value" => "2013-01-01T00:00:00Z"
     * */
    public array $date;

    /*
     * "xml:lang" => "en"
     * "type" => "literal"
     * "value" => "2013 Paris attacks"
     * */
    public array $eventLabel;
}