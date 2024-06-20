<?php

namespace NadLambino\QueryBuilder\Sources;

use Illuminate\Http\Request;
use NadLambino\QueryBuilder\Concerns\Source;

class RequestSource extends Request implements SourceInterface
{
    use Source;

    public static function make(Request $source): self
    {
        return static::createFrom($source, new static());
    }

    public function getData(?string $key = null, $default = null)
    {
        return $this->input($key, $default);
    }
}
