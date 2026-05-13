<?php

namespace App\Repositories\Eloquent;

use App\Models\Metadata;
use App\Data\LandingPage\SearchHeroData;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Repositories\Contratcs\LandingPageRepositoryInterface;

class LandingPageRepository implements LandingPageRepositoryInterface
{
    public function searchHero(string $title, string $year, string $author, string $category, string $study_program): LengthAwarePaginator
    {
        $query = Metadata::query()
            ->with([
                'categories' => fn($query) => $query->where('slug', $category),
                'studyProgram',
                'keywords'
            ])
            ->withCount([
                'activities' => fn($query) => $query->whereHas(
                    'category',
                    fn($query) => $query->where('slug', $category)
                )
            ]);

        $query
            ->where('status', 'approve')
            ->where('visibility', 'public');

        if ($title) {
            $query->whereLike('title', "$title%")
                ->orWhereHas('keywords',
                    fn($query) => $query->whereLike('name', "$title%")
                );
        }

        $query->whereHas(
            'categories',
            fn($query) => $query->where('slug', $category)
        );

        if ($study_program) {
            $query->whereHas(
                'studyProgram',
                fn($query) => $query->where('slug', $study_program)
            );
        }

        if ($year) {
            $query->where('year', $year);
        }

        if ($author) {
            $query->whereLike('author_name', "$author%");
        }

        return SearchHeroData::collect(
            $query->orderByDesc('id')->paginate(8)
        );
    }
}
