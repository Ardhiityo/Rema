<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    public function repository()
    {
        return $this->belongsTo(Repository::class, 'repository_id', 'id');
    }
}
