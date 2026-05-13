<?php

namespace App\Livewire;

use Livewire\Component;
use App\Repositories\Contratcs\AuthorRepositoryInterface;
use App\Repositories\Contratcs\MetaDataRepositoryInterface;
use App\Repositories\Contratcs\CategoryRepositoryInterface;

class MetricCard extends Component
{
    public function getAuthorsCountProperty(AuthorRepositoryInterface $authorRepository)
    {
        return $authorRepository->authorCount();
    }

    public function getRepositoriesCountProperty(MetaDataRepositoryInterface $metaDataRepository)
    {
        return $metaDataRepository->metaDataCount();
    }

    public function getCategoriesCountProperty(CategoryRepositoryInterface $categoryRepository)
    {
        return $categoryRepository->categoryCount();
    }

    public function render()
    {
        return view('livewire.metric-card');
    }
}
