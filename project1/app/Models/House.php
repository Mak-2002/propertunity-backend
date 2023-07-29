<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class House extends Model
{
    use HasFactory;
    protected $guarded = [

    ];
    
    public function property(){
        return $this->morphOne(Property::class, 'category');
    }

    public function rent()
    {   
        //return $this->morphTo();
       
        return $this->morphMany(RentPost::class,'property');
    }

    public function sale()
    {
        return $this->morphMany(salePost::class,'property');
    }
}
