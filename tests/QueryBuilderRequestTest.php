<?php

use NadLambino\QueryBuilder\Sources\RequestSource;

it('can filter nested arrays', function () {
    $expected = [
        'info' => [
            'foo' => [
                'bar' => 1,
            ],
        ],
    ];

    $request = new RequestSource([
        'filter' => $expected,
    ]);

    expect($request->filters()->toArray())->toEqual($expected);
});

it('can get empty filters recursively', function () {
    $request = new RequestSource([
        'filter' => [
            'info' => [
                'foo' => [
                    'bar' => null,
                ],
            ],
        ],
    ]);

    $expected = [
        'info' => [
            'foo' => [
                'bar' => '',
            ],
        ],
    ];

    expect($request->filters()->toArray())->toEqual($expected);
});

it('will map true and false as booleans recursively', function () {
    $request = new RequestSource([
        'filter' => [
            'info' => [
                'foo' => [
                    'bar' => 'true',
                    'baz' => 'false',
                    'bazs' => '0',
                ],
            ],
        ],
    ]);

    $expected = [
        'info' => [
            'foo' => [
                'bar' => true,
                'baz' => false,
                'bazs' => '0',
            ],
        ],
    ];

    expect($request->filters()->toArray())->toEqual($expected);
});

it('can get the sort query param from the request', function () {
    $request = new RequestSource([
        'sort' => 'foobar',
    ]);

    expect($request->sorts()->toArray())->toEqual(['foobar']);
});

it('can get the sort query param from the request body', function () {
    $request = new RequestSource([], [
        'sort' => 'foobar',
    ], [], [], [], ['REQUEST_METHOD' => 'POST']);

    expect($request->sorts()->toArray())->toEqual(['foobar']);
});

it('can get different sort query parameter name', function () {
    config(['query-builder.parameters.sort' => 'sorts']);

    $request = new RequestSource([
        'sorts' => 'foobar',
    ]);

    expect($request->sorts()->toArray())->toEqual(['foobar']);
});

it('will return an empty collection when no sort query param is specified', function () {
    $request = new RequestSource();

    expect($request->sorts())->toBeEmpty();
});

it('can get multiple sort parameters from the request', function () {
    $request = new RequestSource([
        'sort' => 'foo,bar',
    ]);

    $expected = collect(['foo', 'bar']);

    expect($request->sorts())->toEqual($expected);
});

it('will return an empty collection when no sort query params are specified', function () {
    $request = new RequestSource();

    $expected = collect();

    expect($request->sorts())->toEqual($expected);
});

it('can get the filter query params from the request', function () {
    $request = new RequestSource([
        'filter' => [
            'foo' => 'bar',
            'baz' => 'qux',
        ],
    ]);

    $expected = collect([
        'foo' => 'bar',
        'baz' => 'qux',
    ]);

    expect($request->filters())->toEqual($expected);
});

it('can get the filter query params from the request body', function () {
    $request = new RequestSource([], [
            'filter' => [
                'foo' => 'bar',
                'baz' => 'qux',
            ],
        ], [], [], [], ['REQUEST_METHOD' => 'POST']);

    $expected = collect([
            'foo' => 'bar',
            'baz' => 'qux',
        ]);

    expect($request->filters())->toEqual($expected);
});

it('can use different filter query parameter name', function () {
    config(['query-builder.parameters.filter' => 'filters']);

    $request = new RequestSource([
        'filters' => [
            'foo' => 'bar',
            'baz' => 'qux,lex',
        ],
    ]);

    $expected = collect([
        'foo' => 'bar',
        'baz' => ['qux', 'lex'],
    ]);

    expect($request->filters())->toEqual($expected);
});

it('can use null as the filter query parameter name', function () {
    config(['query-builder.parameters.filter' => null]);

    $request = new RequestSource([
        'foo' => 'bar',
        'baz' => 'qux,lex',
    ]);

    $expected = collect([
        'foo' => 'bar',
        'baz' => ['qux', 'lex'],
    ]);

    expect($request->filters())->toEqual($expected);
});

it('can get empty filters', function () {
    config(['query-builder.parameters.filter' => 'filters']);

    $request = new RequestSource([
        'filters' => [
            'foo' => 'bar',
            'baz' => null,
        ],
    ]);

    $expected = collect([
        'foo' => 'bar',
        'baz' => '',
    ]);

    expect($request->filters())->toEqual($expected);
});

it('will return an empty collection when no filter query params are specified', function () {
    $request = new RequestSource();

    $expected = collect();

    expect($request->filters())->toEqual($expected);
});

it('will map true and false as booleans when given in a filter query string', function () {
    $request = new RequestSource([
        'filter' => [
            'foo' => 'true',
            'bar' => 'false',
            'baz' => '0',
        ],
    ]);

    $expected = collect([
        'foo' => true,
        'bar' => false,
        'baz' => '0',
    ]);

    expect($request->filters())->toEqual($expected);
});

it('will map comma separated values as arrays when given in a filter query string', function () {
    $request = new RequestSource([
        'filter' => [
            'foo' => 'bar,baz',
        ],
    ]);

    $expected = collect(['foo' => ['bar', 'baz']]);

    expect($request->filters())->toEqual($expected);
});

it('will map array in filter recursively when given in a filter query string', function () {
    $request = new RequestSource([
        'filter' => [
            'foo' => 'bar,baz',
            'bar' => [
                'foobar' => 'baz,bar',
            ],
        ],
    ]);

    $expected = collect(['foo' => ['bar', 'baz'], 'bar' => ['foobar' => ['baz', 'bar']]]);

    expect($request->filters())->toEqual($expected);
});

it('will map comma separated values as arrays when given in a filter query string and get those by key', function () {
    $request = new RequestSource([
        'filter' => [
            'foo' => 'bar,baz',
        ],
    ]);

    $expected = ['foo' => ['bar', 'baz']];

    expect($request->filters()->toArray())->toEqual($expected);
});

it('can get the include query params from the request', function () {
    $request = new RequestSource([
        'include' => 'foo,bar',
    ]);

    $expected = collect(['foo', 'bar']);

    expect($request->includes())->toEqual($expected);
});

it('can get the include from the request body', function () {
    $request = new RequestSource([], [
        'include' => 'foo,bar',
    ], [], [], [], ['REQUEST_METHOD' => 'POST']);

    $expected = collect(['foo', 'bar']);

    expect($request->includes())->toEqual($expected);
});

it('can get different include query parameter name', function () {
    config(['query-builder.parameters.include' => 'includes']);

    $request = new RequestSource([
        'includes' => 'foo,bar',
    ]);

    $expected = collect(['foo', 'bar']);

    expect($request->includes())->toEqual($expected);
});

it('will return an empty collection when no include query params are specified', function () {
    $request = new RequestSource();

    $expected = collect();

    expect($request->includes())->toEqual($expected);
});

it('can get requested fields', function () {
    $request = new RequestSource([
        'fields' => [
            'table' => 'name,email',
        ],
    ]);

    $expected = collect(['table' => ['name', 'email']]);

    expect($request->fields())->toEqual($expected);
});

it('can get requested fields without a table name', function () {
    $request = new RequestSource([
        'fields' => 'name,email,related.id,related.type',
    ]);

    $expected = collect(['_' => ['name', 'email'], 'related' => ['id', 'type']]);

    expect($request->fields())->toEqual($expected);
});

it('can get nested fields', function () {
    $request = new RequestSource([
        'fields' => [
            'table' => 'name,email',
            'pivots' => 'id,type',
            'pivots.posts' => 'content',
        ],
    ]);

    $expected = collect([
        'table' => ['name', 'email'],
        'pivots' => ['id', 'type'],
        'pivots.posts' => ['content'],
    ]);

    expect($request->fields())->toEqual($expected);
});

it('can get nested fields from a string fields request', function () {
    $request = new RequestSource([
        'fields' => 'id,name,email,pivots.id,pivots.type,pivots.posts.content',
    ]);

    $expected = collect([
        '_' => ['id', 'name', 'email'],
        'pivots' => ['id', 'type'],
        'pivots.posts' => ['content'],
    ]);

    expect($request->fields())->toEqual($expected);
});

it('can get requested fields from the request body', function () {
    $request = new RequestSource([], [
        'fields' => [
            'table' => 'name,email',
        ],
    ], [], [], [], ['REQUEST_METHOD' => 'POST']);

    $expected = collect(['table' => ['name', 'email']]);

    expect($request->fields())->toEqual($expected);
});

it('can get different fields parameter name', function () {
    config(['query-builder.parameters.fields' => 'field']);

    $request = new RequestSource([
        'field' => [
            'column' => 'name,email',
        ],
    ]);

    $expected = collect(['column' => ['name', 'email']]);

    expect($request->fields())->toEqual($expected);
});

it('can get the append query params from the request', function () {
    $request = new RequestSource([
        'append' => 'foo,bar',
    ]);

    $expected = collect(['foo', 'bar']);

    expect($request->appends())->toEqual($expected);
});

it('can get different append query parameter name', function () {
    config(['query-builder.parameters.append' => 'appendit']);

    $request = new RequestSource([
        'appendit' => 'foo,bar',
    ]);

    $expected = collect(['foo', 'bar']);

    expect($request->appends())->toEqual($expected);
});

it('will return an empty collection when no append query params are specified', function () {
    $request = new RequestSource();

    $expected = collect();

    expect($request->appends())->toEqual($expected);
});

it('can get the append query params from the request body', function () {
    $request = new RequestSource([], [
        'append' => 'foo,bar',
    ], [], [], [], ['REQUEST_METHOD' => 'POST']);

    $expected = collect(['foo', 'bar']);

    expect($request->appends())->toEqual($expected);
});

it('takes custom delimiters for splitting request parameters', function () {
    $request = new RequestSource([
        'filter' => [
            'foo' => 'values, contain, commas|and are split on vertical| lines',
        ],
    ]);

    RequestSource::setArrayValueDelimiter('|');

    $expected = ['foo' => ['values, contain, commas', 'and are split on vertical', ' lines']];

    expect($request->filters()->toArray())->toEqual($expected);
});
