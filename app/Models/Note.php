<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $table = 'notes';

    protected $fillable = [
        'meta_data_id',
        'message',
    ];

    public function repository()
    {
        return $this->belongsTo(MetaData::class, 'meta_data_id', 'id');
    }
}
