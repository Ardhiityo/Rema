<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Keyword extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'meta_data_id',
    ];

    public function metadata(): BelongsTo
    {
        return $this->belongsTo(Metadata::class, 'meta_data_id', 'id');
    }
}
