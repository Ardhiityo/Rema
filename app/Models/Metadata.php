<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Metadata extends Model
{
    protected $table = 'meta_data';

    protected $fillable = [
        'title',
        'author_id',
        'author_nim',
        'author_name',
        'study_program_id',
        'visibility',
        'year',
        'slug',
        'status',
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class, 'author_id', 'id');
    }

    public function notes(): HasMany
    {
        return $this->hasMany(Note::class, 'repository_id', 'id');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'meta_data_category', 'meta_data_id', 'category_id')->withPivot('file_path');
    }

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class, 'meta_data_id', 'id');
    }

    public function studyProgram(): BelongsTo
    {
        return $this->belongsTo(StudyProgram::class, 'study_program_id', 'id');
    }
}
