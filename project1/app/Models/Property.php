<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Property extends Model
{
    use HasFactory;

    protected $with = ['image_urls'];
    // protected $without = ['category'];

    public function toArray()
    {
        $data = parent::toArray(); // default Property attributes

        $urls = array_map(function ($item) {
            return $item['url'];
        }, $data['image_urls']);
        $data['image_urls'] = $urls;

        $added =  $this->category->toArray(); // attributes from category child relation
        unset($added['id']);
        unset($added['created_at']);
        unset($added['updated_at']);

        return array_merge($data, $added);
    }

    public function image_urls()
    {
        return $this->hasMany(Image::class);
    }

    public function category()
    {
        return $this->morphTo();
    }
}
