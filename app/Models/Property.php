<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
     protected $fillable = [
        'title',
        'thumbnail_image',
        'property_type',
        'location',
        'price',
        'description',
        'bed',
        'room',
        'bath',
        'square_meter',
        'status',
        'user_id'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function property_images () {
        return $this->hasMany(PropertyImage::class);
    }
}
