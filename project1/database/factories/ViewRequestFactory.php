<?php

namespace Database\Factories;

use App\Models\RentPost;
use App\Models\SalePost;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ViewRequest>
 */
class ViewRequestFactory extends Factory
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
            'sale_post_id'=>SalePost::factory(),
            
        ];
    }
}
