<?php

namespace NadLambino\QueryBuilder\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use NadLambino\QueryBuilder\Tests\TestClasses\Models\TestModel;

class TestModelFactory extends Factory
{
    protected $model = TestModel::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
        ];
    }
}

