<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\MetaData;
use Illuminate\Database\Eloquent\Relations\Pivot;

class MetaDataCategory extends Pivot
{
    protected $table = 'meta_data_category';

    protected $fillable = [
        'meta_data_id',
        'category_id',
        'file_path'
    ];

    public function metadata()
    {
        return $this->belongsTo(Metadata::class, 'meta_data_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
}
