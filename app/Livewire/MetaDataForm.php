<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use App\Data\Author\AuthorListData;
use Illuminate\Support\Facades\Auth;
use App\Data\Metadata\UpdateMetaData;
use App\Data\Metadata\CreateMetadataData;
use App\Repositories\Contratcs\AuthorRepositoryInterface;
use App\Repositories\Contratcs\MetaDataRepositoryInterface;

class MetaDataForm extends Component
{
    // Start Form
    public string $title = '';
    public int|string $author_id = '';
    public string $status = '';
    public string $year = '';
    public string $visibility = '';
    public string $slug = '';
    // End Form

    public int|null $meta_data_id = null;
    public bool $is_update = false;
    public bool $is_approve = false;

    public function mount($meta_data_id = null)
    {
        if ($meta_data_id) {
            $this->createNewForm();
            $this->is_update = true;

            $meta_data_data =  $this->metaDataRepository->findById($meta_data_id);
            $this->meta_data_id = $meta_data_data->id;
            $this->title = $meta_data_data->title;
            $this->year = $meta_data_data->year;
            $this->author_id = $meta_data_data->author_id;
            $this->status = $meta_data_data->status;
            $this->visibility = $meta_data_data->visibility;
            $this->is_approve = $meta_data_data->status == 'approve' ? true : false;
        }

        if ($meta_data_session = $this->metaDataSession) {
            $this->is_update = true;

            $this->meta_data_id = $meta_data_session->id;
            $this->title = $meta_data_session->title;
            $this->author_id = $meta_data_session->author_id;
            $this->status = $meta_data_session->status;
            $this->visibility = $meta_data_session->visibility;
        }

        if (is_null($meta_data_id)) {
            $this->year = now()->year;
        }
    }

    protected function validationAttributes()
    {
        return [
            'slug' => 'title',
            'author_id' => 'author',
            'year' => 'year of graduation',
        ];
    }

    public function createNewForm()
    {
        session()->forget('meta_data');

        $this->is_update = false;

        $this->dispatch('refresh-meta-data-session');

        $this->resetInput();
    }

    #[Computed()]
    public function metaDataSession()
    {
        if (session()->has('meta_data')) {
            $meta_data_session = session()->get('meta_data');
            if ($this->metaDataRepository->findById($meta_data_session->id)) {
                return $meta_data_session;
            } else {
                return $this->createNewForm();
            }
        }

        return false;
    }

    #[Computed()]
    public function islockForm()
    {
        $user = Auth::user();

        if ($user->hasRole('contributor')) {
            return $this->is_approve;
        }
    }

    #[Computed()]
    public function metaDataTitle()
    {
        if ($this->is_update) {
            return 'Edit Meta Data';
        }

        return $this->metaDataSession ? 'Edit Meta Data' : 'Create Meta Data';
    }

    protected function rules(): array
    {
        return [
            'title' => ['required'],
            'slug' =>
            [
                'required',
                'min:3',
                'max:200',
                $this->is_update ? 'unique:meta_data,slug,' . $this->meta_data_id : 'unique:meta_data,slug'
            ],
            'author_id' => ['required', 'exists:authors,id'],
            'year' => ['required', 'date_format:Y'],
            'status' => ['required', 'in:approve,pending,reject,revision'],
            'visibility' => ['required', 'in:private,protected,public']
        ];
    }

    #[Computed()]
    public function metaDataRepository()
    {
        return app(MetaDataRepositoryInterface::class);
    }

    #[Computed()]
    public function authorRepository()
    {
        return app(AuthorRepositoryInterface::class);
    }

    public function createMetaData()
    {
        $user = Auth::user();

        if ($user->hasRole('contributor')) {
            $this->author_id = $user->author->id;
            $this->status = 'pending';
            $this->visibility = 'private';
        }

        $this->slug = Str::slug($this->title);

        $validated = $this->validate();

        $create_meta_data_data = CreateMetadataData::from($validated);

        $meta_data_data = $this->metaDataRepository->create($create_meta_data_data);

        $this->meta_data_id = $meta_data_data->id;

        session()->put('meta_data', $meta_data_data);

        $this->is_update = true;

        $this->dispatch('refresh-meta-data-session');

        session()->flash('succes-meta-data', 'The meta data was successfully created.');
    }

    public function updateMetaData()
    {
        $user = Auth::user();

        if ($user->hasRole('contributor')) {
            $this->author_id = $user->author->id;
            $this->status = 'pending';
            $this->visibility = 'private';
        }

        $this->slug = Str::slug($this->title);

        $validated = $this->validate();

        $update_meta_data_data = UpdateMetaData::from($validated);

        $meta_data_data = $this->metaDataRepository->update(
            $this->meta_data_id,
            $update_meta_data_data
        );

        if (!$this->is_update) {
            session()->put('meta_data', $meta_data_data);
        }

        session()->flash('succes-meta-data', 'The meta data was successfully updated.');

        if ($this->is_update) {
            return redirect()->route('repository.edit', ['meta_data_slug' => $meta_data_data->slug]);
        }
    }

    public function resetInput()
    {
        $this->title = '';
        $this->author_id = '';
        $this->status = '';
        $this->visibility = '';

        $this->resetErrorBag();
    }

    public function render()
    {
        $relations = ['user'];

        $authors = AuthorListData::collect($this->authorRepository->findByApprovals($relations));

        return view('livewire.meta-data-form', compact('authors'));
    }
}
