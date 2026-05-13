<?php

namespace App\Repositories\Contratcs;

use App\Data\Faculty\CreateFacultyData;
use App\Data\Faculty\FacultyData;
use App\Data\Faculty\UpdateFacultyData;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\LaravelData\DataCollection;
use Throwable;

interface FacultyRepositoryInterface
{
    public function create(CreateFacultyData $create_faculty_data): FacultyData|Throwable;

    public function findById(int $faculty_id): FacultyData|Throwable;

    public function update(int $faculty_id, UpdateFacultyData $update_faculty_data): FacultyData|Throwable;

    public function delete(int $faculty_id): bool|Throwable;

    public function all(): DataCollection;

    public function findByFilters(string|null $keyword = null): LengthAwarePaginator;
}