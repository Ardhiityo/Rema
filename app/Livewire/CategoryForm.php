<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use App\Data\Category\CreateCategoryData;
use App\Data\Category\UpdateCategoryData;
use App\Repositories\Contratcs\CategoryRepositoryInterface;

class CategoryForm extends Component
{
    // Start Form
    public string $keyword = '';
    public string $name = '';
    public string $slug = '';
    // End Form

    public int $category_id;
    public bool $is_update = false;

    #[Computed()]
    public function categoryRepository(CategoryRepositoryInterface $categoryRepository)
    {
        return $categoryRepository;
    }

    #[Computed()]
    public function formTitle()
    {
        return $this->is_update ? 'Edit Category' : 'Create New Category';
    }

    protected function rules()
    {
        return [
            'name' => ['required'],
            'slug' => ['required', 'min:3', 'max:50', $this->is_update ? 'unique:categories,slug,' . $this->category_id : 'unique:categories,slug']
        ];
    }

    protected function validationAttributes()
    {
        return [
            'slug' => 'category'
        ];
    }

    public function create()
    {
        $this->slug = Str::slug($this->name);

        $validated = $this->validate();

        $create_category_data = CreateCategoryData::from($validated);

        $this->categoryRepository->create($create_category_data);

        $this->resetInput();

        $this->dispatch('refresh-categories');

        session()->flash('message', 'The category was successfully created.');
    }

    #[On('category-edit')]
    public function edit($category_id)
    {
        $category_data = $this->categoryRepository->findById($category_id);

        $this->name = $category_data->name;

        $this->category_id = $category_data->id;

        $this->is_update = true;
    }

    #[On('category-update')]
    public function update()
    {
        $this->slug = Str::slug($this->name);

        $validated = $this->validate();

        $update_category_data = UpdateCategoryData::from($validated);

        $this->categoryRepository->update($this->category_id, $update_category_data);

        $this->resetInput();

        $this->dispatch('refresh-categories');

        session()->flash('message', 'The category was successfully updated.');
    }

    #[On('category-delete-confirm')]
    public function deleteConfirm($category_id)
    {
        $category_data = $this->categoryRepository->findById($category_id);

        $this->category_id = $category_data->id;
    }

    #[On('category-delete')]
    public function delete()
    {
        $this->categoryRepository->delete($this->category_id);

        $this->dispatch('refresh-categories');

        $this->resetInput();

        session()->flash('message', 'The category was successfully deleted.');
    }

    public function resetInput()
    {
        $this->keyword = '';
        $this->name = '';
        $this->slug = '';

        if ($this->is_update) {
            $this->is_update = false;
        }

        $this->resetErrorBag();
    }

    public function render()
    {
        return view('livewire.category-form');
    }
}
