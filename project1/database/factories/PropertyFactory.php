<?php

namespace Database\Factories;

use App\Models\{Apartment, Commercial, House, Land, Office, Villa};
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Property>
 */
class PropertyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // $categoryType = $this->faker->randomElement(
        //     [Villa::class, Land::class, Apartment::class, Commercial::class, House::class, Office::class]
        // );
        // $category = $categoryType::factory()->create();
        return [
            'user_id' => User::factory(),
            'name' => $this->faker->name,
            'latitude' => $this->faker->latitude(),
            'longitude' => $this->faker->longitude(),
            'address' => $this->faker->address(),
            'about' => $this->faker->text(),
            'area' => $this->faker->randomFloat(),
            'category_type' => $this->faker->randomElement(['Land', 'House', 'Apartment','Commercial','Office','Villa']),
            'category_id' => $this->faker->randomNumber(1, 100),
        ];
    }
}
