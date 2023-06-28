<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class House extends Model
{
    use HasFactory;

    public function rentpost()
    {
        return $this->morphMany('RentPost','property');
    }

    public function salepost()
    {
        return $this->morphMany('salePost','property');
    }

}
