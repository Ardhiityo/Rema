<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Repository;
use App\Data\RepositoryData;

class RepositoryDetail extends Component
{
    public string $title = '';
    public string $abstract = '';
    public string $type = '';
    public string $author = '';
    public string $nim = '';
    public string $published_at = '';
    public string $study_program = '';
    public string $slug = '';

    public function mount(Repository $repository)
    {
        $repository_data = RepositoryData::fromModel(
            $repository->load('author', 'author.studyProgram')
        );

        $this->title = $repository_data->title;
        $this->abstract = $repository_data->abstract;
        $this->type = $repository_data->type;
        $this->author = $repository_data->author_name;
        $this->nim = $repository_data->nim;
        $this->slug = $repository_data->slug;
        $this->published_at = $repository_data->publised_at_to_ymd;
        $this->study_program = $repository_data->study_program;
    }

    public function render()
    {
        return view('livewire.repository-detail');
    }
}
