<?php

namespace NadLambino\QueryBuilder\Filters;

/**
 * @template TModelClass of \Illuminate\Database\Eloquent\Model
 * @template-implements \NadLambino\QueryBuilder\Filters\Filter<TModelClass>
 */
class FiltersEndsWithStrict extends FiltersPartial implements Filter
{
    protected function getWhereRawParameters($value, string $property, string $driver): array
    {

        return [
            "{$property} LIKE ?".static::maybeSpecifyEscapeChar($driver),
            ['%'.static::escapeLike($value)],
        ];
    }
}
