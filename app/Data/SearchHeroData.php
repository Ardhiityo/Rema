<?php

namespace App\Data;

use App\Models\Repository;
use Carbon\Carbon;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\Computed;

class SearchHeroData extends Data
{
    #[Computed]
    public string $published_at_to_dfy;
    #[Computed]
    public string $published_at_to_diff_for_humans;
    #[Computed]
    public string $icon_class;

    public function __construct(
        public string $author,
        public string $title,
        public string $type,
        public string $slug,
        public string $published_at
    ) {
        $date = Carbon::parse($published_at);
        $this->published_at_to_dfy = $date->format('d F Y');
        $this->published_at_to_diff_for_humans = $date->diffForHumans();
        $this->icon_class = $type == 'thesis' ? "fas fa-book-reader fa-3x" : ($type == 'final_project' ? "fas fa-book fa-3x" : "fas fa-journal-whills fa-3x");
    }

    public static function fromModel(Repository $repository)
    {
        return new self(
            $repository->author->name,
            $repository->title,
            $repository->type,
            $repository->slug,
            $repository->published_at
        );
    }
}
