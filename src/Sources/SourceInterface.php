<?php

namespace NadLambino\QueryBuilder\Sources;

interface SourceInterface
{
    public function getData(?string $key = null, $default = null);
}
