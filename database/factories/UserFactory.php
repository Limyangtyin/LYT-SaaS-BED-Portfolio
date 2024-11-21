<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nickname' => $this->faker->userName(),
            'given_name' => $this->faker->firstName(),
            'family_name' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'company_id' => null,
            'user_type' => $this->faker->randomElement(['client', 'staff', 'applicant', 'administrator', 'super-user']),
            'status' => $this->faker->randomElement(['active', 'unconfirmed', 'suspended', 'banned', 'unknown']),
            'password' => Hash::make('Password1')
        ];
    }
}
