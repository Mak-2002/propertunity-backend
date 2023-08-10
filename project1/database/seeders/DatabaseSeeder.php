<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Favorite;
use App\Models\House;
use App\Models\Office;
use App\Models\Apartment;
use App\Models\Commercial;
use App\Models\Land;
use App\Models\Property;
use App\Models\Rating;
use App\Models\RatingAspect;
use App\Models\RentContract;
use App\Models\RentPost;
use App\Models\RentRequest;
use App\Models\Review;
use App\Models\SaleContract;
use App\Models\SalePost;
use App\Models\SaleRequest;
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
        // Users Seeding
        User::factory(20)->create();

        // Properties Seeding
        Property::factory(30)->create();

        // SalePosts Seeding
        SalePost::factory(2)->create([
            'user_id' => 2,
            'property_id' => 1,
        ]);
        SalePost::factory(10)->create();

        // RentPosts Seeding
        RentPost::factory(2)->create([
            'user_id' => 2,
            'property_id' => 1,
        ]);
        RentPost::factory(10)->create();

        // // ViewRequests Seeding
        // ViewRequest::factory(5)->create([
        //     'sale_post_id' => 3,
        //     'rent_post_id' => null
        // ]);

        // ViewRequest::factory(5)->create([
        //     'rent_post_id' => 2,
        //     'sale_post_id' => null
        // ]);

         // SaleRequests Seeding
         SaleRequest::factory(10)->create();
         // RentRequests Seeding
        RentRequest::factory(10)->create();

        //  // SaleContracts Seeding
        // SaleContract::factory(10)->create();
        //  // RentContracts Seeding
        // RentContract::factory(10)->create();

        //Reviews and Ratings Seeding
        Rating::factory(10)->create();
        RatingAspect::factory(10)->create();
        Review::factory(10)->create();

        // Favorites Seeding
        Favorite::factory(5)->create([
            'user_id' => rand(1, 20),
            'rent_post_id' => rand(3, 11),
        ]);

        Favorite::factory(5)->create([
            'user_id' => rand(1, 20),
            'sale_post_id' => rand(3, 11),
        ]);
    }
}
