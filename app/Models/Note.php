<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $table = 'notes';

    public function repository()
    {
        return $this->belongsTo(MetaData::class, 'repository_id', 'id');
    }
}
