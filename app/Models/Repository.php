<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Repository extends Model
{
    protected $casts = [
        'published_at' => 'datetime'
    ];

    public function author()
    {
        return $this->belongsTo(Author::class, 'author_id', 'id');
    }
}
