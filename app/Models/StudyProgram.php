<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class StudyProgram extends Model
{
    protected $table = 'study_programs';

    public function authors()
    {
        return $this->hasMany(Author::class, 'study_program_id', 'id');
    }

    public function coordinators(): HasOne
    {
        return $this->hasOne(Coordinator::class, 'study_program_id', 'id');
    }
}
