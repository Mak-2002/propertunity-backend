<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ViewPlan extends Model
{
    use HasFactory;

    public function rentPost()
    {
        return $this->hasMany(RentPost::class);
    }

    public function salePost()
    {
        return $this->hasMany(SalePost::class);
    }
}
