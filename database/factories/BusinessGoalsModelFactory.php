<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BusinessGoalModel>
 */
class BusinessGoalsModelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = \App\Models\BusinessGoalsModel::class;

    public function definition()
    {
        return [
            'crc_app' => $this->faker->numberBetween(0, 100), // Số ngẫu nhiên từ 0 đến 100
            'crc_loan' => $this->faker->numberBetween(0, 100),
            'plxs_app' => $this->faker->numberBetween(0, 100),
            'plxs_loan' => $this->faker->numberBetween(0, 100),
            'amount_plxs' => $this->faker->numberBetween(0, 1000000), // Số ngẫu nhiên từ 0 đến 1,000,000
            'banca' => $this->faker->numberBetween(0, 100),
            'loan_ctbs' => $this->faker->numberBetween(0, 100),
            'convert_banca' => $this->faker->numberBetween(0, 100),
            'ctbs' => $this->faker->numberBetween(0, 100),
        ];
    }
}