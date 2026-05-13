<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Staff extends Model
{
   protected $fillable = [
    'user_id',
    'faculty_id',
   ];

   public function user(): BelongsTo
   {
    return $this->belongsTo(User::class, 'user_id', 'id');
   }

   public function faculty(): BelongsTo
   {
    return $this->belongsTo(Faculty::class, 'faculty_id', 'id');
   }
}
