<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RentPost extends Model
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('visibility', function (Builder $builder) {
            $builder->where('visibility', true);
        });

        static::addGlobalScope('approval', function (Builder $builder) {
            $builder->where('approval', true);
        });
    }
    protected $fillable = ['approval'];
    protected $guarded = [
        'id',
        'user_id',

        // other sensitive attributes
    ];

    protected $with = [
        'property', 'rating'
    ];

    public function toArray()
    {
        $data = parent::toArray();
        $data['posttype'] = 'rent';
        if (isset($data['rating'])) {
            $rating = array_map(function ($item) {
                unset($item['id']);
                unset($item['rent_post_id']);
                $item['rating_aspect'] = RatingAspect::findOrFail($item['rating_aspect_id'])->name;
                unset($item['sum']);
                unset($item['created_at']);
                unset($item['updated_at']);
                return $item;
            }, $data['rating']);
            $data['rating'] = $rating;
        }
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

    public function favorable_by()
    {
        return $this->hasMany(Favorite::class);
    }

    public function rating()
    {
        return $this->hasMany(Rating::class);
    }

    protected function scopeFilter($query, array $filters)
    {

        $query->when($filters['search'] ?? false, fn ($query, $search_phrase) =>
        $query->whereHas('property', fn ($query) =>
        $query->where('address', 'like', '%' . $search_phrase . '%')));

        $query->when(
            $filters['category'] ?? false,
            fn ($query, $category) =>
            $query->whereHas('property', function ($query) use ($category) {
                $query->where('category_type', $category);
            })
        );

        $query->when($filters['rooms'] ?? false, fn ($query, $rooms) =>
        $query->whereHas('property', function ($query) use ($rooms) {
            $query->whereHasMorph('category', ['Office', 'House', 'Villa', 'Commercial', 'Apartment'], function ($query) use ($rooms) {
                $query->where('room_count', $rooms);
            });
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
