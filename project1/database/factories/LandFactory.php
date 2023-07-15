<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Land>
 */
class LandFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id'=> User::factory(),
            'name'=>$this->faker->name(),
            'address'=>$this->faker->text,
            'about'=>$this->faker->text,
            '360_view'=>$this->faker->text,
            'image_library'=>$this->faker->text,
            'area'=>$this->faker->randomNumber()
        ];
    }
}
