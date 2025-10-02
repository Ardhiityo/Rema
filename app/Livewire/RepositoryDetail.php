<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Repository;
use App\Data\RepositoryData;

class RepositoryDetail extends Component
{
    public string $title = '';
    public string $abstract = '';
    public string $category = '';
    public string $author = '';
    public string|null $nim = '';
    public string $created_at = '';
    public string|null $study_program = '';
    public string $slug = '';

    public function mount(Repository $repository)
    {
        $repository_data = RepositoryData::fromModel(
            $repository->load('author', 'author.studyProgram')
        );

        $this->title = $repository_data->title;
        $this->abstract = $repository_data->abstract;
        $this->category = $repository_data->category_name;
        $this->author = $repository_data->author_name;
        $this->nim = $repository_data->nim;
        $this->slug = $repository_data->slug;
        $this->created_at = $repository_data->created_at;
        $this->study_program = $repository_data->study_program;
    }

    public function render()
    {
        return view('livewire.repository-detail');
    }
}
