<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Metadata extends Model
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

    public function repositories()
    {
        return $this->belongsTo(Repository::class, 'meta_data_id', 'id');
    }
}
