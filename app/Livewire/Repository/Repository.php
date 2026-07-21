<?php

namespace App\Livewire\Repository;

use App\Repositories\Contratcs\KeywordRepositoryInterface;
use App\Repositories\Contratcs\MetaDataCategoryRepositoryInterface;
use App\Repositories\Contratcs\MetaDataRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Throwable;

class Repository extends Component
{
    public bool $is_update = false;

    public bool $is_approve = false;

    public ?int $meta_data_id = null;

    public bool $is_categories_empty = false;

    public function mount($meta_data_slug = null)
    {
        try {
            if (! empty($meta_data_slug)) {

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
            return empty($meta_data_slug) ? '' : redirect()->route('repository.create');
        }
    }

    #[Computed()]
    public function keywordRepository()
    {
        return app(KeywordRepositoryInterface::class);
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
            throw new Exception;
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
            logger()->error($th->getMessage(), ['Repository' => 'isEditRepositoryCategoryForm']);
            Session::flash('repository-failed', 'Failed getting meta data');
        }
    }

    #[Computed()]
    #[On('refresh-meta-data-session')]
    public function showMetaDataKeywordForm()
    {
        if ($this->is_update) {
            return $this->is_approve && Auth::user()->hasRole('author') ? false : true;
        }

        return $this->metaDataSession;
    }

    #[Computed()]
    #[On('refresh-meta-data-session')]
    public function showMetaDataKeywordList()
    {
        try {
            $meta_data_id = $this->metaDataSession ? $this->metaDataSession->id : $this->meta_data_id;

            $meta_data = $this->keywordRepository->findByMetaDataId($meta_data_id);

            if ($meta_data->toCollection()->isNotEmpty()) {
                return true;
            }

            throw new Exception;
        } catch (Throwable $th) {
            return false;
        }
    }

    #[Computed()]
    #[On('refresh-meta-data-keyword')]
    public function showMetaDataCategoryForm()
    {
        if ($this->is_update) {
            return $this->is_approve && Auth::user()->hasRole('author') ? false : true;
        } elseif ($meta_data_id = $this->metaDataSession ? $this->metaDataSession->id : $this->meta_data_id) {
            return $this->keywordRepository->findByMetaDataId($meta_data_id)->toCollection()->count() == 3 ? true : false;
        }

        return false;
    }

    #[Computed()]
    #[On('refresh-meta-data-category')]
    public function showMetaDataCategoryList()
    {
        try {
            $meta_data_id = $this->metaDataSession ? $this->metaDataSession->id : $this->meta_data_id;

            $meta_data = $this->metaDataRepository->findById(meta_data_id: $meta_data_id, relations: ['categories']);

            if ($meta_data->categories->toCollection()->isNotEmpty()) {
                return true;
            }

            throw new Exception;
        } catch (Throwable $th) {
            return false;
        }
    }

    public function render()
    {
        return view('livewire.repository.index');
    }
}
