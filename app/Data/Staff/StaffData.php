<?php

declare(strict_types=1);

namespace App\Data\Staff;

use App\Models\Staff;
use Spatie\LaravelData\Data;

class StaffData extends Data
{
    public function __construct(
        public int|string $id,
        public int|string $user_id,
        public string|null $name,
        public int|null|string $faculty_id
    ) {}

    public static function fromModel(Staff $staff)
    {
        return new self(
            $staff->id,
            $staff->user_id,
            $staff->user?->name,
            $staff->faculty_id
        );
    }
}
