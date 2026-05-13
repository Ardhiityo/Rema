<?php

declare(strict_types=1);

namespace App\Data\Staff;

use Spatie\LaravelData\Data;

class CreateStaffData extends Data
{
    public function __construct(
        public int|string $user_id,
        public int|string $faculty_id
    ) {}
}
