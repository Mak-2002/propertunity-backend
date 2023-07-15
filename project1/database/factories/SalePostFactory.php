<?php

namespace Database\Factories;

use App\Models\Property;
use App\Models\User;
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
    // public function definition(): array
    // {
    //     $property =$this->faker->randomElement([House::class, Apartment::class,Villa::class,Office::class,Commercial::class,Land::class]);
    //     return [
            
    //         'view_plan_id'=>ViewPlan::factory(),
    //         'user_id'=>User::factory(),
    //         'price'=>$this->faker->randomNumber(),
    //         'visibility'=>$this->faker->boolean,
    //         'property_type' => $property,
    //         'property_id' => $property->id,

    //     ];
    // }

    public function definition()
    {
        $property = $this->faker->randomElement([Post::class, Video::class]);

        return [
            'view_plan_id'=>ViewPlan::factory(),
            'user_id'=>User::factory(),
            'price'=>$this->faker->randomNumber(),
            'visibility'=>$this->faker->boolean,
            'property_id' => $this->faker->randomNumber(1, 100),
            'property_type' => $this->faker->randomElement(['Land', 'House', 'Apartment','Commercial','Office','Villa']),

        ];
    }
}
