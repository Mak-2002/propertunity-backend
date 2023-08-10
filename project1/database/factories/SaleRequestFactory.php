<?php

namespace Database\Factories;

use App\Models\Property;
use App\Models\User;
use App\Models\ViewPlan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SaleRequest>
 */
class SaleRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'view_plan_id'=>ViewPlan::factory(),
            'user_id'=>User::factory(),
            'price'=>$this->faker->randomNumber(),
            'property_id' => Property::factory(),
        ];
    }
}
