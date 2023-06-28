<?php

namespace Database\Factories;

use App\Models\RatingAspect;
use App\Models\RentPost;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Rating>
 */
class RatingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'rent_post_id'=>RentPost::factory(),
            'rating_aspect_id'=>RatingAspect::factory(),
            'sum'=>$this->faker->randomNumber(),
            'count'=>$this->faker->randomNumber(),

        ];
    }
}
