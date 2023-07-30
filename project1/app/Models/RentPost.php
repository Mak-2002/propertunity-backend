<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RentPost extends Model
{
    use HasFactory;
    protected $guarded = [
        'id',
        'user_id',

        // other sensitive attributes
    ];

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

    public function favorable_by()
    {
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

        $query->when($filters['search'] ?? false, fn ($query, $search_phrase) =>
        $query->whereHas('property', fn ($query) =>
        $query->where('address', 'like', '%' . $search_phrase . '%')));

        $query->when(
            $filters['type'] ?? false,
            fn ($query, $type) =>
            $query->where('property_type', $type)
        );

        $query->when($filters['rooms'] ?? false, fn ($query, $rooms) =>
        $query->whereHasMorph('property', ['Office', 'House', 'Villa', 'Commercial', 'Apartment'], function ($query) use ($rooms) {
            $query->where('room_count', $rooms);
        }));

        $query->when(isset($filters['area_min']) && isset($filters['area_max']), function ($query) use ($filters) {
            $query->whereHas('property', function ($query) use ($filters) {
                $query->whereBetween('area', [$filters['area_min'], $filters['area_max']]);
            });
        });

        $query->when(isset($filters['price_min']) && isset($filters['price_max']), function ($query) use ($filters) {
            $query->whereBetween('monthly_rent', [$filters['price_min'], $filters['price_max']]);
        });
    }
}
