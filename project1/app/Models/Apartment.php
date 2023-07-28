<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Apartment extends Model
{
    use HasFactory;
    protected $guarded = [

    ];
    
    public function property(){
        return $this->morphOne(Property::class, 'category');
    }


    
}
