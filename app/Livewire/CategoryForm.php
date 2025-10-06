<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Category;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Log;

class CategoryForm extends Component
{
    public string $keyword = '';
    public string $name = '';
    public string $slug = '';
    public int $category_id;
    public bool $is_update = false;

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

        Category::create($validated);

        $this->resetInput();

        $this->dispatch('refresh-categories');

        return session()->flash('message', 'The category was successfully created.');
    }

    #[On('category-edit')]
    public function edit($category_id)
    {
        $category = Category::find($category_id);

        $this->name = $category->name;

        $this->category_id = $category->id;

        $this->is_update = true;
    }


    #[On('category-update')]
    public function update()
    {
        $this->slug = Str::slug($this->name);

        $validated = $this->validate();

        $category = Category::find($this->category_id);

        $category->update($validated);

        $this->is_update = false;

        $this->resetInput();

        $this->dispatch('refresh-categories');

        return session()->flash('message', 'The category was successfully updated.');
    }

    #[On('category-delete-confirm')]
    public function deleteConfirm($category_id)
    {
        $category = Category::find($category_id);

        $this->category_id = $category->id;
    }

    #[On('category-delete')]
    public function delete()
    {
        Category::find($this->category_id)->delete();

        $this->dispatch('refresh-categories');

        return session()->flash('message', 'The category was successfully deleted.');
    }

    public function resetInput()
    {
        $this->keyword = '';
        $this->name = '';
        $this->slug = '';

        $this->resetErrorBag();
    }

    public function render()
    {
        return view('livewire.category-form');
    }
}
