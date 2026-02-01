<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $table = 'categories';

    protected $fillable = [
        'name',
        'slug',
    ];

    public function metadata()
    {
        return $this->belongsToMany(
            Metadata::class,
            'meta_data_category',
            'category_id',
            'meta_data_id'
        )->withPivot('file_path');
    }

    public function activities(): HasMany
    {
        return $this->hasMany(Metadata::class, 'category_id', 'id');
    }
}
