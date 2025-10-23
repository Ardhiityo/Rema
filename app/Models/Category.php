<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $table = 'categories';

    public function metadata()
    {
        return $this->belongsToMany(MetaData::class, 'meta_data_category', 'category_id', 'meta_data_id')->withPivot('file_path');
    }

    public function activities(): HasMany
    {
        return $this->hasMany(MetaData::class, 'category_id', 'id');
    }
}
