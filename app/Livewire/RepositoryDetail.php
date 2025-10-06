<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Repository;
use App\Data\RepositoryData;
use Illuminate\Support\Facades\Auth;

class RepositoryDetail extends Component
{
    public string $title = '';
    public string $abstract = '';
    public string|null $avatar = '';
    public string $status = '';
    public string $badge_status = '';
    public string $category = '';
    public string $author = '';
    public string|null $nim = '';
    public string $created_at = '';
    public string|null $study_program = '';
    public string $slug = '';
    public int $repository_id;
    public bool $is_admin;
    public Repository $repository;

    public function mount(Repository $repository)
    {
        $this->repository = $repository;
        $repository_data = RepositoryData::fromModel(
            $repository->load('author', 'author.studyProgram')
        );
        $user = Auth::user();
        $this->is_admin = $user->hasRole('admin') ? true : false;
        $this->repository_id = $repository_data->id;
        $this->title = $repository_data->title;
        $this->status = $repository_data->ucfirst_status;
        $this->badge_status = $repository_data->badge_status;
        $this->abstract = $repository_data->abstract;
        $this->category = $repository_data->category_name;
        $this->author = $repository_data->author_name;
        $this->avatar = $repository_data->author_avatar;
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
