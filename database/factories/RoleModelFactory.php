<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RoleModel>
 */
class RoleModelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = \App\Models\RoleModel::class;
    public function definition(): array
    {
        return [
            'group_name' => $this->faker->unique(), // 
            'description' => $this->faker->sentence, // 
        ];
    }
}
