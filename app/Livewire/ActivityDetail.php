<?php

namespace App\Livewire;

use App\Repositories\Contratcs\ActivityRepositoryInterface;
use Livewire\Component;
use Livewire\WithPagination;

class ActivityDetail extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public string $category_slug = '';
    public string $meta_data_slug = '';

    public function mount($category_slug = '', $meta_data_slug = '')
    {
        $this->category_slug = $category_slug;
        $this->meta_data_slug = $meta_data_slug;
    }

    public function getActivitiesProperty(ActivityRepositoryInterface $activityRepository)
    {
        return $activityRepository->findByMetaDataSlugAndCategorySlug(
            $this->category_slug,
            $this->meta_data_slug
        );
    }

    public function render()
    {
        return view('livewire.activity-detail');
    }
}
