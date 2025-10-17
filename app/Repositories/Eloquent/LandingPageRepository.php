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
        $query = MetaData::query()->with([
            'author',
            'categories' => fn($query) => $query->where('slug', $category),
            'author.user',
            'author.studyProgram'
        ]);

        $query->where('status', 'approve')->where('visibility', 'public');

        if ($title) {
            $query->whereLike('title', "%$title%");
        }

        $query->whereHas(
            'categories',
            fn($query) => $query->where('slug', $category)
        );

        if ($year) {
            $query->where('year', $year);
        }

        if ($author) {
            $query->whereHas(
                'author.user',
                fn($query) => $query->whereLike('name', "%$author%")
            );
        }

        return SearchHeroData::collect(
            $query->orderByDesc('id')->paginate(8)
        );
    }
}
