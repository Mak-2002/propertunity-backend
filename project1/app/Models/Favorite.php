<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function salePost()
    {
        return $this->belongsTo(SalePost::class);
    }

    public function rentPost()
    {
        return $this->belongsTo(RentPost::class);
    }
}
