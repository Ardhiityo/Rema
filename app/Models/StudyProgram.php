<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Faculty;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class StudyProgram extends Model
{
    protected $table = 'study_programs';

    protected $fillable = [
        'name',
        'slug',
        'faculty_id',
    ];

    public function authors(): HasMany
    {
        return $this->hasMany(Author::class, 'study_program_id', 'id');
    }

    public function coordinators(): HasOne
    {
        return $this->hasOne(Coordinator::class, 'study_program_id', 'id');
    }

    public function metadata(): HasMany
    {
        return $this->hasMany(Metadata::class, 'study_program_id', 'id');
    }

    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class, 'faculty_id', 'id');
    }
}
