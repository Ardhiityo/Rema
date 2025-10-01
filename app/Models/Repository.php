<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Repository extends Model
{
    public function author()
    {
        return $this->belongsTo(Author::class, 'author_id', 'id');
    }

    public function notes()
    {
        return $this->hasMany(Note::class, 'repository_id', 'id');
    }
}
