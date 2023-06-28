<?php

namespace Database\Factories;

use App\Models\RentPost;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RatingAspect>
 */
class RatingAspectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'=>$this->faker->name,
            'thumbnail'=>$this->faker->text,
            
        ];
    }
}
