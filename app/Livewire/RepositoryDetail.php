<?php

namespace App\Livewire;

use App\Data\MetadataData;
use App\Data\RepositoryData;
use Livewire\Component;
use App\Models\Metadata;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Spatie\LaravelData\DataCollection;

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
    public MetaData $meta_data;
    public  $repositories;

    public function mount(Metadata $meta_data)
    {
        $this->meta_data = $meta_data;
        $metadata_data = MetadataData::fromModel(
            $meta_data->load('author', 'author.studyProgram', 'repositories')
        );
        $user = Auth::user();
        $this->repositories = $meta_data->load('repositories')->repositories;
        $this->is_admin = $user->hasRole('admin') ? true : false;
        $this->repository_id = $metadata_data->id;
        $this->title = $metadata_data->title;
        $this->status = $metadata_data->ucfirst_status;
        $this->badge_status = $metadata_data->badge_status;
        $this->abstract = $metadata_data->abstract;
        $this->author = $metadata_data->author_name;
        $this->avatar = $metadata_data->author_avatar;
        $this->nim = $metadata_data->nim;
        $this->slug = $metadata_data->slug;
        $this->created_at = $metadata_data->created_at;
        $this->study_program = $metadata_data->study_program;
    }

    public function render()
    {
        return view('livewire.repository-detail');
    }
}
