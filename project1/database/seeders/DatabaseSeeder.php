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
        // Admin Seeding
        $admin = User::factory()->create([
            'name' => 'admin',
            'phone' => '0000000000',
            'password' => bcrypt('propertunityadmin'),
            'verified' => true,
        ])->first();

        // Users Seeding
        $user = User::factory()->create([
            'name' => 'qusai',
            'phone' => '1111111111',
            'password' => bcrypt('20012002'),
            'verified' => true,
        ])->first();

        User::factory(20)->create();

        // Properties Seeding
        Property::factory(30)->create();

        RatingAspect::factory()->create([
            'name' => 'services'
        ]);
        RatingAspect::factory()->create([
            'name' => 'location'
        ]);
        RatingAspect::factory()->create([
            'name' => 'cleanliness'
        ]);


        // SalePosts Seeding
        SalePost::factory(2)->create([
            'user_id' => 2,
            'property_id' => 1,
            'approval' => 1,
            'visibility' => 1,
        ]);
        SalePost::factory(2)->create([
            'user_id' => 2,
            'property_id' => 1,
            'approval' => null
        ]);
        SalePost::factory(10)->create();

        // RentPosts Seeding
        RentPost::factory(2)->create([
            'user_id' => 2,
            'property_id' => 1,
            'approval' => 1,
            'visibility' => 1,
        ]);
        RentPost::factory(2)->create([
            'user_id' => 2,
            'property_id' => 1,
            'approval' => null
        ]);
        RentPost::factory(10)->create();

        // Favorites Seeding
        Favorite::factory(2)->create([
            'user_id' => 2,
            'rent_post_id' => rand(2, 10),
            'sale_post_id' => null,
        ])->each(function ($favorite) {
            $rent_post = RentPost::withoutGlobalScopes()->findOrFail($favorite->rent_post_id);
            $rent_post->setRelation('favorable_by', $favorite);
            $rent_post->save();
        });

        Favorite::factory(1)->create([
            'user_id' => 2,
            'rent_post_id' => null,
            'sale_post_id' => rand(2, 10),
        ])->each(function ($favorite) {
            $sale_post = SalePost::withoutGlobalScopes()->findOrFail($favorite->sale_post_id);
            $sale_post->setRelation('favorable_by', $favorite);
            $sale_post->save();
        });

        // ViewRequests Seeding
        // ViewRequest::factory(5)->create([
        //     'sale_post_id' => 3,
        //     'rent_post_id' => null
        // ]);

        // ViewRequest::factory(5)->create([
        //     'rent_post_id' => 2,
        //     'sale_post_id' => null
        // ]);

        //  // SaleRequests Seeding
        //  SaleRequest::factory(10)->create();
        //  // RentRequests Seeding
        // RentRequest::factory(10)->create();

        //  // SaleContracts Seeding
        // SaleContract::factory(10)->create();
        //  // RentContracts Seeding
        // RentContract::factory(10)->create();

        //Ratings Seeding
        Rating::factory(10)->create();
    }
}
