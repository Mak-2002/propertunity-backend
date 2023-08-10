<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleRequest extends Model
{
    use HasFactory;

    protected $with = [
        'property'
    ];

    public function toArray() {
        $data = parent::toArray();
        $data['posttype'] = 'sale';
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
