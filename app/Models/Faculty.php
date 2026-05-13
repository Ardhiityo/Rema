<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\StudyProgram;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Faculty extends Model
{
    protected $fillable = [
        'name',
        'slug',
    ];

    public function studyPrograms(): HasMany
    {
        return $this->hasMany(StudyProgram::class, 'faculty_id', 'id');
    }

    public function staff(): HasMany
    {
        return $this->hasMany(Staff::class, 'faculty_id', 'id');
    }
}
