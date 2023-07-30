<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Office>
 */
class OfficeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'room_count'=>$this->faker->randomNumber(),
            'bathroom_count'=>$this->faker->randomNumber(),
            'kitchen_count'=>$this->faker->randomNumber(),
            'storey'=>$this->faker->randomNumber(),
            'balkony'=>$this->faker->randomNumber(),
            'parking'=>$this->faker->boolean(),
            'security_cameras'=>$this->faker->boolean(),
            'Wi-Fi'=>$this->faker->boolean(),
            'security_gard'=>$this->faker->boolean()
        ];
    }
}
