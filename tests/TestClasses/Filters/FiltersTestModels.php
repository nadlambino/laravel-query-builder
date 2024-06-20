<?php

namespace NadLambino\QueryBuilder\Tests\TestClasses\Filters;

use Illuminate\Database\Eloquent\Builder;
use NadLambino\QueryBuilder\Filters\Filter;

/**
 * @template TModelClass of \Illuminate\Database\Eloquent\Model
 * @template-implements \NadLambino\QueryBuilder\Filters\Filter<TModelClass>
 */
class FiltersTestModels implements Filter
{
    /** {@inheritdoc} */
    public function __invoke(Builder $query, $value, string $property): Builder
    {
        return $query->where($property, $value);
    }
}
