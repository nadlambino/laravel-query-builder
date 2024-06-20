<?php

namespace NadLambino\QueryBuilder;

use NadLambino\QueryBuilder\Sources\RequestSource;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class QueryBuilderServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-query-builder')
            ->hasConfigFile();
    }

    public function registeringPackage(): void
    {
        $this->app->bind(RequestSource::class, function ($app) {
            return RequestSource::make($app['request']);
        });
    }

    public function provides(): array
    {
        return [
            RequestSource::class,
        ];
    }
}
