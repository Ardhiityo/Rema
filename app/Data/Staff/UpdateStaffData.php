<?php

declare(strict_types=1);

namespace App\Data\Staff;

use Spatie\LaravelData\Data;

class UpdateStaffData extends Data
{
    public function __construct(
        public int|string $faculty_id
    ) {}
}
