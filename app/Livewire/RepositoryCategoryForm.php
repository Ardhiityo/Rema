<?php

namespace App\Livewire;

use Throwable;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;
use Livewire\Attributes\Computed;
use App\Rules\MetaDataCategoryCreateRule;
use App\Rules\MetaDataCategoryUpdateRule;
use App\Data\MetadataCategory\CreateMetadataCategoryData;
use App\Data\MetadataCategory\UpdateMetadataCategoryData;
use App\Repositories\Contratcs\CategoryRepositoryInterface;
use App\Repositories\Contratcs\MetaDataRepositoryInterface;
use App\Repositories\Contratcs\MetaDataCategoryRepositoryInterface;

class RepositoryCategoryForm extends Component
{
    use WithFileUploads;

    // Start Form
    public int|null $meta_data_id = null;
    public int|string $category_id = '';
    public $file_path = null;
    // End Form

    public string|null $file_path_update = null;
    public int|null $category_id_update = null;
    public int|null $category_id_delete = null;
    public int|string $repository_id = '';
    public bool $is_update = false;

    public function mount($meta_data_id = null)
    {
        if ($this->metaDataSession) {
            $this->meta_data_id = $this->metaDataSession->id;
        }

        if ($meta_data_id) {
            $this->meta_data_id = $meta_data_id;
        }
    }

    #[Computed()]
    public function title()
    {
        return $this->is_update ? 'Edit Repository' : 'Create Repository';
    }

    protected function rulesCreate(): array
    {
        return [
            'file_path' => ['required', 'file', 'mimes:pdf', 'max:7000'],
            'category_id' => [
                'required',
                'exists:categories,id',
                new MetaDataCategoryCreateRule($this->meta_data_id)
            ],
            'meta_data_id' => ['required', 'exists:meta_data,id']
        ];
    }

    protected function rulesUpdate(): array
    {
        return [
            'file_path' => ['nullable', 'file', 'mimes:pdf', 'max:7000'],
            'category_id' => [
                'required',
                'exists:categories,id',
                new MetaDataCategoryUpdateRule($this->meta_data_id, $this->category_id_update)
            ],
            'meta_data_id' => ['required', 'exists:meta_data,id']
        ];
    }

    protected function validationAttributes()
    {
        return [
            'file_path' => 'file',
            'author_id' => 'author',
            'category_id' => 'category'
        ];
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
    public function categoryRepository()
    {
        return app(CategoryRepositoryInterface::class);
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

    public function createRepository()
    {
        $validated = $this->validate($this->rulesCreate());

        try {
            $create_meta_data_category_data = CreateMetadataCategoryData::from($validated);

            $this->metaDataCategoryRepository->create($create_meta_data_category_data);

            $this->resetInput();

            $this->dispatch('refresh-repository-table');

            return session()->flash('repository-success', 'The repository was successfully created.');
        } catch (Throwable $th) {
            return session()->flash('repository-failed', $th->getMessage());
        }
    }

    #[On('edit-repository-category')]
    public function editRepository($meta_data_slug, $category_slug)
    {
        try {
            $meta_data_category_data = $this->metaDataCategoryRepository
                ->findByMetaDataSlugAndCategorySlug($meta_data_slug, $category_slug);

            $this->meta_data_id = $meta_data_category_data->meta_data_id;
            $this->category_id = $meta_data_category_data->category_id;

            $this->category_id_update = $meta_data_category_data->category_id;
            $this->file_path_update = $meta_data_category_data->file_path;

            $this->is_update = true;
        } catch (Throwable $th) {
            return session()->flash('repository-failed', $th->getMessage());
        }
    }

    public function updateRepository()
    {
        $validated = $this->validate($this->rulesUpdate());

        try {
            $update_meta_data_category_data = UpdateMetadataCategoryData::from($validated);

            $this->metaDataCategoryRepository->update(
                $update_meta_data_category_data,
                $this->category_id_update
            );

            $this->resetInput();

            $this->is_update = false;

            $this->dispatch('refresh-repository-table');

            session()->flash('repository-success', 'The repository was successfully updated.');
        } catch (Throwable $th) {
            session()->flash('repository-failed', $th->getMessage());
        }
    }

    #[On('delete-confirm-repository-category')]
    public function deleteConfirm($meta_data_slug, $category_slug)
    {
        try {
            $meta_data_category_data = $this->metaDataCategoryRepository
                ->findByMetaDataSlugAndCategorySlug($meta_data_slug, $category_slug);

            $this->meta_data_id = $meta_data_category_data->meta_data_id;
            $this->category_id_delete = $meta_data_category_data->category_id;
        } catch (Throwable $th) {
            session()->flash('repository-failed', $th->getMessage());
        }
    }

    #[On('delete-repository-category')]
    public function delete()
    {
        try {
            $this->metaDataCategoryRepository->delete($this->meta_data_id, $this->category_id_delete);

            $meta_data_data = $this->metaDataRepository->findById($this->meta_data_id, ['categories']);

            if ($meta_data_data) {
                if ($meta_data_data->categories->toCollection()->isEmpty()) {
                    $this->resetInput();
                }
            }

            $this->dispatch('refresh-repository-table');

            session()->flash('repository-success', 'The repository was successfully deleted.');
        } catch (Throwable $th) {
            session()->flash('repository-failed', $th->getMessage());
        }
    }

    public function resetInput()
    {
        $this->file_path = null;
        $this->category_id = '';
        $this->is_update = false;

        $this->resetErrorBag();
    }

    public function render()
    {
        $categories = $this->categoryRepository->all();

        return view('livewire.repository-category-form', compact('categories'));
    }
}
