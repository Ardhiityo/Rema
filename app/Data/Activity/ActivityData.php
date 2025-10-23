<?php

namespace App\Data\Activity;

use App\Models\Activity;
use Illuminate\Support\Str;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\Computed;

class ActivityData extends Data
{
    #[Computed()]
    public string $short_ip;
    #[Computed()]
    public string $short_user_agent;
    #[Computed()]
    public string $short_name;
    #[Computed()]
    public string $short_meta_data;
    #[Computed()]
    public string $short_category;

    public function __construct(
        public int $id,
        public string $ip,
        public string $user_agent,
        public string $name,
        public string $meta_data,
        public string $category
    ) {
        $this->short_ip = Str::limit($ip, 20, '...');
        $this->short_user_agent = Str::limit($user_agent, 20, '...');
        $this->short_name = Str::limit($name, 20, '...');
        $this->short_meta_data = Str::limit($meta_data, 20, '...');
        $this->short_category = Str::limit($category, 20, '...');
    }

    public static function fromModel(Activity $activity): self
    {
        return new self(
            $activity->id,
            $activity->ip,
            $activity->user_agent,
            $activity?->user?->name ?? '-',
            $activity->metadata->title,
            $activity->category->name
        );
    }
}
