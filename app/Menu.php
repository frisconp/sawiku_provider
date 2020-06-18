<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Menu extends Model
{
    public function getPhotoAttribute($photo)
    {
        return Storage::url($photo);
    }

    public function category()
    {
        return $this->belongsTo('App\MenuCategory', 'menu_category_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
