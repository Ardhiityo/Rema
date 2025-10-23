<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';

    public function metadata()
    {
        return $this->belongsToMany(MetaData::class, 'meta_data_category', 'category_id', 'meta_data_id')->withPivot('file_path');
    }
}
