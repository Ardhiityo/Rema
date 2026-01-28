<?php

namespace App\Repositories\Eloquent;

use App\Models\MetaData;
use App\Data\LandingPage\SearchHeroData;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Repositories\Contratcs\LandingPageRepositoryInterface;

class LandingPageRepository implements LandingPageRepositoryInterface
{
    public function searchHero(string $title, string $year, string $author, string $category): LengthAwarePaginator
    {
        $query = MetaData::query()
            ->with([
                'categories' => fn($query) => $query->where('slug', $category)
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
            $query->whereLike('title', "$title%");
        }

        $query->whereHas(
            'categories',
            fn($query) => $query->where('slug', $category)
        );

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
