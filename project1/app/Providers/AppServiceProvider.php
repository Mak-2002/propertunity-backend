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
             'RentPost'=>'App\Models\RentPost',
             'SalePost'=>'App\Models\SalePost' ,
             'Favorite'=>'App\Models\Favorite',
             'Rating'=>'App\Models\Rating' ,
             'RatingAspect'=>'App\Models\RatingAspect',
             'Review'=>'App\Models\Review',
             'VerificationCode'=>'App\Models\VerificationCode',
             'ViewPlan'=>'App\Models\ViewPlan',
             'ViewRequest'=>'App\Models\ViewRequest'
        ]);
    //    // Relation::morphMap([], 'User' , 'RentPost', 'SalePost' , 'Favorite','Rating' ,'RatingAspect','Review','VerificationCode','ViewPlan','ViewRequest');
     }
}
