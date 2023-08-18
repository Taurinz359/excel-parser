<?php

namespace Database\Factories;

use App\Models\Row;
use Illuminate\Database\Eloquent\Factories\Factory;

class RowFactory extends Factory
{
    protected $model = Row::class;

    /**
     * @inheritDoc
     */
    public function definition()
    {
        return [
            'excel_id' => $this->faker->uuid,
            'name' => $this->faker->name,
            'date' => $this->faker->dateTime->format('d.m.Y'),
        ];
    }
}
