<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RentPost extends Model
{
    use HasFactory;
    public function property()
    {
        return $this->morphTo();
    }
    public function viewPlan()
    {
        return $this->hasMany(ViewPlan::class);
    }

    public function favorable_by() {
        return $this->hasMany(Favorite::class);
    }

    public function rating()
    {
        return $this->hasMany(Rating::class);
    }
    public function review()
    {
        return $this->hasMany(Review::class);
    }

    protected function scopeFilter($query, array $filters)
    {
        
        $query->when($filters['search'] ?? false, fn($query, $search_phrase) =>
            $query->whereHas('property', fn($query) =>
                $query->where('address', 'like', '%' . $search_phrase . '%')));

        $query->when($filters['type'] ?? false, fn($query, $search_phrase) =>
            $query->whereHas('property', fn($query) =>
                $query->where('property_type_id', $search_phrase)));

        $query->when($filters['area'] ?? false, fn($query, $search_phrase) =>
            $query->whereHas('property', fn($query) =>
                $query->where('area', $search_phrase)));

        $query->when($filters['rooms'] ?? false, fn($query, $search_phrase) =>
            $query->whereHas('property', fn($query) =>
                $query->where('room_count', $search_phrase)));
        
    }

}
