<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Favorite;
use App\Models\House;
use App\Models\Office;
use App\Models\Apartment;
use App\Models\Rating;
use App\Models\RatingAspect;
use App\Models\RentPost;
use App\Models\Review;
use App\Models\SalePost;
use Illuminate\Database\Seeder;
use \App\Models\User;
use App\Models\ViewPlan;
use App\Models\ViewRequest;
use App\Models\Villa;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
    User::factory(10)->create();
    SalePost::factory(10)->create();
    RentPost::factory(10)->create();
    House::factory(10)->create();
    Villa::factory(10)->create();
    Office::factory(10)->create();
    Apartment::factory(10)->create();
    ViewPlan::factory(10)->create();
    ViewRequest::factory(5)->create([
        'sale_post_id'=>3,
        'rent_post_id'=>null
    ]);
    ViewRequest::factory(5)->create([
        'rent_post_id'=>2,
        'sale_post_id'=>null

    ]);
    Rating::factory(10)->create();
    RatingAspect::factory(10)->create();
    Review::factory(10)->create();
    Favorite::factory(10)->create();



        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        //  ]);
    }
}
