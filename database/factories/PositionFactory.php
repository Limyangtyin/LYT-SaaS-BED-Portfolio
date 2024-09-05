<?php

namespace Database\Factories;

use App\Models\Position;
use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Position>
 */
class PositionFactory extends Factory
{
    protected $model = Position::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        return [
            'advertising_start_date' => $this->faker->date(),
            'advertising_end_date' => $this->faker->date(),
            'position_title' => $this->faker->jobTitle(),
            'position_description' => $this->faker->paragraph(),
            'position_keywords' => $this->faker->words(3, true),
            'minimum_salary' => $this->faker->randomFloat(2, 0, 10000),
            'maximum_salary' => $this->faker->randomFloat(2, 10000, 100000),
            'salary_currency' => $this->faker->currencyCode(),
            'company_id' => Company::factory(),
            'benefits' => $this->faker->sentence(),
            'requirements' => $this->faker->sentence(),
            'position_type' => $this->faker->randomElement(['permanent', 'contract', 'part-time', 'casual', 'internship']),
        ];
    }
}
