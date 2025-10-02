<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function studyProgram()
    {
        return $this->belongsTo(StudyProgram::class, 'study_program_id', 'id');
    }

    public function repositories()
    {
        return $this->hasMany(Repository::class, 'author_id', 'id');
    }
}
