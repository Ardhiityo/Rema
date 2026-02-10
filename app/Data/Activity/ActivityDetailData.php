<?php

declare(strict_types=1);

namespace App\Data\Activity;

use App\Models\Activity;
use Spatie\LaravelData\Data;
use Illuminate\Support\Facades\Storage;

class ActivityDetailData extends Data
{
    public function __construct(
        public string $avatar,
        public string $name,
        public string $nim,
        public string $study_program,
        public string $ip,
        public string $user_agent,
        public string $created_at
    ) {}

    public static function fromModel(Activity $activity): self
    {
        return new self(
            $activity?->user?->avatar ? Storage::url($activity->user->avatar) : asset('assets/compiled/jpg/anonym.jpg'),
            $activity?->user?->name ?? 'Anonym',
            $activity?->user->author?->nim ?? '-',
            $activity?->user?->author?->studyProgram?->name ?? '-',
            $activity->ip,
            $activity?->user_agent,
            $activity->created_at->format('d F Y H:i:s')
        );
    }
}
