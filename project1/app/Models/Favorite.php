<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(Rating::class);
    }

    public function salePost()
    {
        return $this->belongsTo(Rating::class);
    }

    public function rentPost()
    {
        return $this->belongsTo(Rating::class);
    }
}
