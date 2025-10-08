<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudyProgram extends Model
{
    protected $table = 'study_programs';

    public function authors()
    {
        return $this->hasMany(Author::class, 'study_program_id', 'id');
    }
}
