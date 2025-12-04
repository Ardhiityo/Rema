<?php

namespace App\Livewire;

use Exception;
use Throwable;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Contratcs\MetaDataRepositoryInterface;
use App\Repositories\Contratcs\MetaDataCategoryRepositoryInterface;
use Illuminate\Support\Facades\Session;

use function Pest\Laravel\session;

class RepositoryForm extends Component
{
    public bool $is_update = false;
    public int|null $meta_data_id = null;
    public bool $is_approve = false;
    public bool $is_categories_empty = false;

    public function mount($meta_data_slug = null)
    {
        try {
            if (!empty($meta_data_slug)) {

                $meta_data_data = $this->metaDataRepository->findBySlug($meta_data_slug);

                $this->authorize('update', $meta_data_data->toModel());

                $this->meta_data_id = $meta_data_data->id;

                $this->is_approve = $meta_data_data->status == 'approve' ? true : false;

                $this->is_categories_empty = $meta_data_data->categories->toCollection()->isEmpty() ? true : false;

                $this->is_update = true;

                if (Session::has('meta_data')) {
                    Session::forget('meta_data');
                }
            }
        } catch (Throwable $th) {
            return empty($meta_data_slug) ? '' :  redirect()->route('repository.create');
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
        try {
            if (Session::has('meta_data')) {
                return $this->metaDataRepository->findById(
                    Session::get('meta_data')->id
                );
            }
            throw new Exception();
        } catch (Throwable $th) {
            return false;
        }
    }

    #[Computed()]
    #[On('edit-repository-category')]
    public function isEditRepositoryCategoryForm($meta_data_slug, $category_slug)
    {
        try {
            $this->metaDataCategoryRepository
                ->findByMetaDataSlugAndCategorySlug($meta_data_slug, $category_slug);

            return $this->showMetaDataCategoryForm;
        } catch (Throwable $th) {
            session()->flash('repository-failed', $th->getMessage());
        }
    }

    #[Computed()]
    #[On('refresh-meta-data-session')]
    public function showMetaDataCategoryForm()
    {
        if ($this->is_update) {
            return $this->is_approve && Auth::user()->hasRole('contributor') ? false : true;
        }

        return $this->metaDataSession;
    }

    #[Computed()]
    #[On('refresh-repository-table')]
    public function showMetaDataCategoryTable()
    {
        try {
            $meta_data_id = $this->metaDataSession ? $this->metaDataSession->id : $this->meta_data_id;

            $meta_data = $this->metaDataRepository->findById(meta_data_id: $meta_data_id, relations: ['categories']);

            if ($meta_data->categories->toCollection()->isNotEmpty()) {
                return true;
            }

            throw new Exception();
        } catch (Throwable $th) {
            return false;
        }
    }

    public function render()
    {
        return view('livewire.repository-form');
    }
}
