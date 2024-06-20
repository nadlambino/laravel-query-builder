<?php

namespace NadLambino\QueryBuilder\Exceptions;

use Exception;
use NadLambino\QueryBuilder\Enums\SortDirection;

class InvalidDirection extends Exception
{
    public static function make(string $sort)
    {
        return new static('The direction should be either `'.SortDirection::DESCENDING.'` or `'.SortDirection::ASCENDING)."`. {$sort} given.";
    }
}
