<?php

namespace Database\Factories;

use App\Models\Property;
use App\Models\User;
use App\Models\ViewPlan;
use Illuminate\Database\Eloquent\Factories\Factory;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RentPost>
 */
class RentPostFactory extends Factory
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
    //     'user_id'=>User::factory(),
    //     'view_plan_id'=>ViewPlan::factory(),
    //     'monthly_rent'=>$this->faker->randomNumber(),
    //     'max_duration'=>$this->faker->randomDigitNot(0),
    //     'visibility'=>$this->faker->boolean,
    //     'property_type' => $property,
    //     'property_id' => $property->id,


    //     ];
    // }

    public function definition()
    {
        $property = $this->faker->randomElement([Post::class, Video::class]);

        return [
            'user_id'=>User::factory(),
            'view_plan_id'=>ViewPlan::factory(),
            'monthly_rent'=>$this->faker->randomNumber(),
            'max_duration'=>$this->faker->randomDigitNot(0),
            'visibility'=>$this->faker->boolean,
            'approval'=>$this->faker->boolean,
            'rejection_purpose' => $this->faker->text(),
            'property_id' => Property::factory(),
        ];
    }
}
