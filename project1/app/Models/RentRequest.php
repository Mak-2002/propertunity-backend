<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RentRequest extends Model
{
    use HasFactory;

    public function toArray() {
        $data = parent::toArray();
        $data['posttype'] = 'rent';
        return $data;
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function property()
    {
        return $this->belongsTo(Property::class);
    }
    public function viewPlan()
    {
        return $this->hasMany(ViewPlan::class);
    }
}
