<?php

namespace Database\Factories;

use App\Models\Property;
use App\Models\ViewPlan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SalePost>
 */
class SalePostFactory extends Factory
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
            'price'=>$this->faker->randomNumber(),
            'visibility'=>$this->faker->boolean,
        ];
    }
}
