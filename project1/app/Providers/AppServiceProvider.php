<?php

namespace App\Providers;

use App\Models\RentPost;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Relation::MorphMap([
             'Land'=> 'App\Models\Land',
             'House'=>'App\Models\House', 
             'Apartment'=>'App\Models\Apartment',
             'Commercial'=>'App\Models\Commercial',
             'Office'=>'App\Models\Office',
             'Villa'=>'App\Models\Villa',
              'User'=>'App\Models\User',
             'RentPost'=>'App\Models\RentPost'::class,
             'SalePost'=>'App\Models\SalePost'::class ,
             'Favorite'=>'App\Models\Favorite'::class,
             'Rating'=>'App\Models\Rating'::class ,
             'RatingAspect'=>'App\Models\RatingAspect'::class,
             'Review'=>'App\Models\Review'::class,
             'VerificationCode'=>'App\Models\VerificationCode'::class,
             'ViewPlan'=>'App\Models\ViewPlan'::class,
             'ViewRequest'=>'App\Models\ViewRequest'::class
        ]);
    //    // Relation::morphMap([], 'User' , 'RentPost', 'SalePost' , 'Favorite','Rating' ,'RatingAspect','Review','VerificationCode','ViewPlan','ViewRequest');
     }
}
