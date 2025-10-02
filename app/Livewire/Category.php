<?php

namespace App\Livewire;

use App\Data\CategoryData;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;

class Category extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public string $keyword = '';
    public string $name = '';
    public string $slug = '';
    public int|null $category_id = null;
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

        \App\Models\Category::create($validated);

        $this->resetInput();

        return session()->flash('message', 'The category was successfully created.');
    }

    public function edit($category_slug)
    {
        $category = \App\Models\Category::where('slug', $category_slug)->first();

        $this->name = $category->name;
        $this->category_id = $category->id;

        $this->is_update = true;
    }

    public function update()
    {
        $this->slug = Str::slug($this->name);

        $validated = $this->validate();

        $category = \App\Models\Category::find($this->category_id);

        $category->update($validated);

        $this->is_update = false;

        $this->resetInput();

        return session()->flash('message', 'The category was successfully updated.');
    }

    public function deleteConfirm($category_slug)
    {
        $category = \App\Models\Category::where('slug', $category_slug)->first();

        $this->category_id = $category->id;
    }

    public function delete()
    {
        \App\Models\Category::find($this->category_id)->delete();

        return session()->flash('message', 'The category was successfully deleted.');
    }

    public function resetInput()
    {
        $this->reset();
        $this->resetPage();
        $this->resetErrorBag();
    }
    public function render()
    {
        $query = \App\Models\Category::query();

        if ($keyword = $this->keyword) {
            $query->whereLike('name', "%$keyword%");
        }

        $categories = CategoryData::collect(
            $query->orderByDesc('id')->paginate(10)
        );

        return view('livewire.category', compact('categories'));
    }
}
