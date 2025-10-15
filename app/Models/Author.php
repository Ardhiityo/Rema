<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Attributes\Scope;

class Author extends Model
{
    protected $table = 'authors';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function studyProgram()
    {
        return $this->belongsTo(StudyProgram::class, 'study_program_id', 'id');
    }

    public function metadata()
    {
        return $this->hasMany(MetaData::class, 'author_id', 'id');
    }

    #[Scope]
    public function approve(Builder $query)
    {
        $query->where('status', 'approve');
    }
}
