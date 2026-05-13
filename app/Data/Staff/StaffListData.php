<?php

declare(strict_types=1);

namespace App\Data\Staff;

use App\Models\Staff;
use Illuminate\Support\Str;
use Spatie\LaravelData\Data;
use Illuminate\Support\Facades\Storage;
use Spatie\LaravelData\Attributes\Computed;

class StaffListData extends Data
{
    #[Computed]
    public string $short_name;

    public function __construct(
        public int|string $id,
        public string $name,
        public string|null $faculty,
        public string|null $avatar,
    ) {
        $this->short_name = Str::limit($name, 15, '...');
    }

    public static function fromModel(Staff $staff): self
    {
        return new self(
            $staff->id,
            $staff->user->name,
            $staff->faculty->name ?? 'No Faculty',
            $staff->user?->avatar ? Storage::url($staff->user->avatar) : asset('assets/compiled/jpg/anonym.jpg')
        );
    }
}
