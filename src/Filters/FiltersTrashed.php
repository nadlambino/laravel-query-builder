<?php

namespace NadLambino\QueryBuilder\Filters;

use Illuminate\Database\Eloquent\Builder;

/**
 * @template TModelClass of \Illuminate\Database\Eloquent\Model
 * @template-implements \NadLambino\QueryBuilder\Filters\Filter<TModelClass>
 */
class FiltersTrashed implements Filter
{
    /** {@inheritdoc} */
    public function __invoke(Builder $query, $value, string $property)
    {
        if ($value === 'with') {
            $query->withTrashed();

            return;
        }

        if ($value === 'only') {
            $query->onlyTrashed();

            return;
        }

        $query->withoutTrashed();
    }
}
