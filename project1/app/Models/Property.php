<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use HasFactory;

    // protected $with = ['category'];
    protected $without = ['category'];

    public function toArray() {

        $data = parent::toArray(); // default Property attributes
        $data['category_type'] = class_basename($data['category_type']);
        $added = $this->category->toArray(); // attributes from category child relation
        return array_merge($data, $added);
    }

    public function image_urls() {
        return $this->hasMany(Image::class);
    }

    public function category() {
        return $this->morphTo();
    }
}
