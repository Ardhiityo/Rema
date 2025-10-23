<?php

namespace App\Models;

use App\Models\MetaData;
use Illuminate\Database\Eloquent\Relations\Pivot;

class MetaDataCategory extends Pivot
{
    protected $table = 'meta_data_category';

    public function metadata()
    {
        return $this->belongsTo(MetaData::class, 'meta_data_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
}
