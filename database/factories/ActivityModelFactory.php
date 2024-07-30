<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Activity>
 */
class ActivityModelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = \App\Models\ActivityModel::class;
    public function definition(): array
    {
        return [
            'name' => $this->faker->word, // Tạo từ ngẫu nhiên
            'type' => $this->faker->randomElement(['Truyền thống 1', 'Truyền thống 2', 'Truyền thống 3']),
        ];
    }
}
