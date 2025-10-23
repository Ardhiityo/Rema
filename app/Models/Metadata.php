<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MetaData extends Model
{
    protected $table = 'meta_data';

    public function author()
    {
        return $this->belongsTo(Author::class, 'author_id', 'id');
    }

    public function notes()
    {
        return $this->hasMany(Note::class, 'repository_id', 'id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'meta_data_category', 'meta_data_id', 'category_id')->withPivot('file_path');
    }

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class, 'meta_data_id', 'id');
    }
}
