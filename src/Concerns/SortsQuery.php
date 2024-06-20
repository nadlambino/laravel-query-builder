<?php

namespace NadLambino\QueryBuilder\Concerns;

use Illuminate\Support\Collection;
use NadLambino\QueryBuilder\AllowedSort;
use NadLambino\QueryBuilder\Exceptions\InvalidSortQuery;

trait SortsQuery
{
    protected Collection $allowedSorts;

    public function allowedSorts($sorts): static
    {
        $sorts = is_array($sorts) ? $sorts : func_get_args();

        $this->allowedSorts = collect($sorts)->map(function ($sort) {
            if ($sort instanceof AllowedSort) {
                return $sort;
            }

            return AllowedSort::field(ltrim($sort, '-'));
        });

        $this->ensureAllSortsExist();

        $this->addRequestedSortsToQuery(); // allowed is known & source is known, add what we can, if there is no source, -wait

        return $this;
    }

    public function defaultSort(AllowedSort|array|string $sorts): static
    {
        $sorts = is_array($sorts) ? $sorts : func_get_args();

        return $this->defaultSorts($sorts);
    }

    public function defaultSorts(AllowedSort|array|string $sorts): static
    {
        if ($this->source->sorts()->isNotEmpty()) {
            // We've got requested sorts. No need to parse defaults.

            return $this;
        }

        $sorts = is_array($sorts) ? $sorts : func_get_args();

        collect($sorts)
            ->map(function ($sort) {
                if ($sort instanceof AllowedSort) {
                    return $sort;
                }

                return AllowedSort::field($sort);
            })
            ->each(fn (AllowedSort $sort) => $sort->sort($this));

        return $this;
    }

    protected function addRequestedSortsToQuery(): void
    {
        $this->source->sorts()
            ->each(function (string $property) {
                $descending = $property[0] === '-';

                $key = ltrim($property, '-');

                $sort = $this->findSort($key);

                $sort?->sort($this, $descending);
            });
    }

    protected function findSort(string $property): ?AllowedSort
    {
        return $this->allowedSorts
            ->first(fn (AllowedSort $sort) => $sort->isSort($property));
    }

    protected function ensureAllSortsExist(): void
    {
        if (config('query-builder.disable_invalid_sort_query_exception', false)) {
            return;
        }

        $requestedSortNames = $this->source->sorts()->map(fn (string $sort) => ltrim($sort, '-'));

        $allowedSortNames = $this->allowedSorts->map(fn (AllowedSort $sort) => $sort->getName());

        $unknownSorts = $requestedSortNames->diff($allowedSortNames);

        if ($unknownSorts->isNotEmpty()) {
            throw InvalidSortQuery::sortsNotAllowed($unknownSorts, $allowedSortNames);
        }
    }
}
