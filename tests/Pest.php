<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use NadLambino\QueryBuilder\QueryBuilder;
use NadLambino\QueryBuilder\Tests\TestCase;
use NadLambino\QueryBuilder\Tests\TestClasses\Models\TestModel;

uses(TestCase::class)->in(__DIR__);

function createQueryFromFilterRequest(array $filters, string $model = null): QueryBuilder
{
    $model ??= TestModel::class;

    $request = new Request([
        'filter' => $filters,
    ]);

    return QueryBuilder::for($model, $request);
}

function createQueryFromFilterCollection(array $filters, string $model = null): QueryBuilder
{
    $model ??= TestModel::class;

    $collection = collect([
        'filter' => $filters,
    ]);

    return QueryBuilder::for($model, $collection);
}

function assertQueryExecuted(string $query)
{
    $queries = array_map(function ($queryLogItem) {
        return $queryLogItem['query'];
    }, DB::getQueryLog());

    expect($queries)->toContain($query);
}
