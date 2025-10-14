<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\MetaData;
use Livewire\Attributes\On;
use Livewire\Attributes\Computed;
use App\Repositories\Contratcs\MetaDataRepositoryInterface;
use App\Repositories\Contratcs\MetaDataCategoryRepositoryInterface;

class RepositoryForm extends Component
{
    public bool $is_update = false;
    public bool $is_edit_repository_category_form = false;
    public string $meta_data_slug = '';

    public function mount()
    {
        if (request()->routeIs('repository.edit')) {
            $this->meta_data_slug = request()->route('meta_data_slug');
            if ($meta_data_data = $this->metaDataRepository->findBySlug($this->meta_data_slug)) {
                $this->authorize('update', $meta_data_data->toModel());
                $this->is_update = true;
                session()->forget('meta_data');
            } else {
                return redirect()->route('repository.create');
            }
        }
    }

    #[Computed()]
    public function metaDataRepository()
    {
        return app(MetaDataRepositoryInterface::class);
    }

    #[Computed()]
    public function metaDataCategoryRepository()
    {
        return app(MetaDataCategoryRepositoryInterface::class);
    }

    #[Computed()]
    public function metaDataSession()
    {
        if (session()->has('meta_data')) {

            $meta_data_session = session()->get('meta_data');

            if ($this->metaDataRepository->findById($meta_data_session->id)) {
                return $meta_data_session;
            }
        }

        return false;
    }

    #[Computed()]
    #[On('edit-repository-category')]
    public function isEditRepositoryCategoryForm($meta_data_slug, $category_slug)
    {
        $metadata_categories_exist = $this->metaDataCategoryRepository
            ->findByMetaDataSlugAndCategorySlug($meta_data_slug, $category_slug);

        if ($metadata_categories_exist) {
            $this->is_edit_repository_category_form = true;

            return $this->showRepositoryCategoryFrom;
        }
    }

    #[Computed()]
    #[On('refresh-meta-data-session')]
    public function showRepositoryCategoryFrom()
    {
        if ($is_update = $this->is_update) {
            return $is_update;
        }

        return $this->metaDataSession;
    }

    #[Computed()]
    #[On('refresh-repository-table')]
    public function showRepositoriesList()
    {
        if ($this->is_update) {
            if ($this->metaDataRepository->findBySlug($this->meta_data_slug)) {
                return true;
            }
        }

        if ($meta_data_session = $this->metaDataSession) {
            $meta_data = MetaData::find($meta_data_session->id);

            if ($meta_data->categories->isNotEmpty()) {
                return true;
            }
        }

        return false;
    }

    public function render()
    {
        return view(
            'livewire.repository-form',
        );
    }
}
