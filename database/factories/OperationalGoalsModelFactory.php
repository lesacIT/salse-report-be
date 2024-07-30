<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OperationalGoalsModel>
 */
class OperationalGoalsModelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = \App\Models\OperationalGoalsModel::class;

    public function definition()
    {
        return [
            'day' => $this->faker->date(),
            'code_staff' => $this->faker->uuid, // Tạo UUID ngẫu nhiên
            'fullname_staff' => $this->faker->name,
            'area' => $this->faker->word,
            'time1' => $this->faker->time(),
            'action1' => $this->faker->sentence,
            'group_action1' => $this->faker->word,
            'time2' => $this->faker->time(),
            'action2' => $this->faker->sentence,
            'group_action2' => $this->faker->word,
            'time3' => $this->faker->time(),
            'action3' => $this->faker->sentence,
            'group_action3' => $this->faker->word,
            'time4' => $this->faker->time(),
            'action4' => $this->faker->sentence,
            'group_action4' => $this->faker->word,
        ];
    }
}
