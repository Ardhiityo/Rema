<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Author extends Model
{
    protected $table = 'authors';

    protected $fillable = [
        'nim',
        'user_id',
        'study_program_id'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function studyProgram(): BelongsTo
    {
        return $this->belongsTo(StudyProgram::class, 'study_program_id', 'id');
    }

    public function metadata(): HasMany
    {
        return $this->hasMany(MetaData::class, 'author_id', 'id');
    }

    #[Scope]
    public function approve(Builder $query): void
    {
        $query->where('status', 'approve');
    }
}
