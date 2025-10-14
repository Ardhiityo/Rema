<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Author;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use App\Data\Author\AuthorListData;
use Illuminate\Support\Facades\Auth;
use App\Data\Metadata\CreateMetaData;
use App\Data\MetaData\UpdateMetaData;
use App\Repositories\Contratcs\MetaDataRepositoryInterface;

class MetaDataForm extends Component
{
    // Start Form
    public string $title = '';
    public string $abstract = '';
    public int|string $author_id = '';
    public string $status = '';
    public string $visibility = '';
    public string $slug = '';
    // End Form

    public int|null $meta_data_id = null;
    public bool $is_update = false;
    public bool $is_approve = false;

    public function mount()
    {
        if (request()->routeIs('repository.edit')) {
            $meta_data_slug = request()->route('meta_data_slug');
            $meta_data =  $this->metaDataRepository->findBySlug($meta_data_slug);
            $this->createNewForm();
            $this->meta_data_id = $meta_data->id;
            $this->title = $meta_data->title;
            $this->slug = $meta_data->slug;
            $this->abstract = $meta_data->abstract;
            $this->author_id = $meta_data->author_id;
            $this->status = $meta_data->status;
            $this->visibility = $meta_data->visibility;
            $this->is_approve = $meta_data->status == 'approve' ? true : false;
            $this->is_update = true;
        }

        if ($meta_data_session = $this->metaDataSession) {
            $this->meta_data_id = $meta_data_session->id;
            $this->title = $meta_data_session->title;
            $this->abstract = $meta_data_session->abstract;
            $this->author_id = $meta_data_session->author_id;
            $this->status = $meta_data_session->status;
            $this->visibility = $meta_data_session->visibility;
            $this->is_update = true;
        }
    }

    protected function validationAttributes()
    {
        return [
            'slug' => 'title',
            'author_id' => 'author',
        ];
    }

    public function createNewForm()
    {
        session()->forget('meta_data');

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
        return 'Create Meta Data';
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
            'abstract' => ['required', 'min:3', 'max:2000'],
            'author_id' => ['required', 'exists:authors,id'],
            'status' => ['required', 'in:approve,pending,reject,revision'],
            'visibility' => ['required', 'in:private,protected,public']
        ];
    }

    #[Computed()]
    public function metaDataRepository()
    {
        return app(MetaDataRepositoryInterface::class);
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

        $validated['year'] = Carbon::now()->year;

        $create_meta_data_data = CreateMetaData::from($validated);

        $meta_data_data = $this->metaDataRepository->create($create_meta_data_data);

        $this->meta_data_id = $meta_data_data->id;

        session()->put('meta_data', $meta_data_data);

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
        $this->abstract = '';
        $this->author_id = '';
        $this->status = '';
        $this->visibility = '';

        $this->resetErrorBag();
    }

    public function render()
    {
        $authors = AuthorListData::collect(
            Author::with('user')
                ->where('status', 'approve')->get()
        );

        return view('livewire.meta-data-form', compact('authors'));
    }
}
