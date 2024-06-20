<?php

namespace NadLambino\QueryBuilder\Database\Factories;

use NadLambino\QueryBuilder\Tests\TestClasses\Models\AppendModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class AppendModelFactory extends Factory
{
    protected $model = AppendModel::class;

    public function definition()
    {
        return [
            'firstname' => $this->faker->firstName,
            'lastname' => $this->faker->lastName,
        ];
    }
}
