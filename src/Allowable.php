<?php

namespace NadLambino\QueryBuilder;

use NadLambino\QueryBuilder\Sources\SourceInterface;

trait Allowable
{
    protected static SourceInterface $source;

    public static function setSource(SourceInterface $source): void
    {
        static::$source = $source;
    }

    protected static function getSourceClass(): string
    {
        return get_class(static::$source);
    }
}
