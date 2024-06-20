<?php

namespace NadLambino\QueryBuilder\Sources;

use Illuminate\Support\Collection;
use NadLambino\QueryBuilder\Concerns\Source;

class CollectionSource extends Collection implements SourceInterface
{
    use Source;

    public function getData(?string $key = null, $default = null)
    {
        return $this->get($key, $default);
    }
}
