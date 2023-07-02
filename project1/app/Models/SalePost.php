<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalePost extends Model
{
    use HasFactory;

    public function property()
    {
        return $this->morphTo();
        // return $this->belongsTo(Apartment::class,Commercial::class,House::class,Land::class,Office::class,Villa::class);
    }

    public function viewPlan()
    {
        return $this->hasMany(ViewPlan::class);
    }

    public function favorable_by() {
        return $this->hasMany(Favorite::class);
    }

    protected function scopeFilter($query, array $filters)
    {
        
        $query->when($filters['search'] ?? false, fn($query, $search_phrase) =>
            $query->whereHas('property', fn($query) =>
                $query->where('address', 'like', '%' . $search_phrase . '%')));

        $query->when($filters['type'] ?? false, fn($query, $search_phrase) =>
            $query->whereHas('property', fn($query) =>
                $query->where('property_type_id', $search_phrase)));

        $query->when($filters['min'] ?? false,
         fn($query, $min,$max) =>
            $query->whereHas('property', fn($query) =>
                $query->whereBetween('price', [$min, $max])

                // $query->where('area', $search_phrase)
            ));

        $query->when($filters['rooms'] ?? false, fn($query, $search_phrase) =>
            $query->whereHas('property', fn($query) =>
                $query->where('room_count', $search_phrase)));
        
    }
}
