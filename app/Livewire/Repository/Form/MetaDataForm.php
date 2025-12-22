<?php

namespace App\Livewire\Repository\Form;

use Throwable;
use Livewire\Component;
use Illuminate\Support\Str;
use App\Data\Author\AuthorData;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Auth;
use App\Data\Metadata\UpdateMetaData;
use Spatie\LaravelData\DataCollection;
use Illuminate\Support\Facades\Session;
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

    public string $keyword = '';
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
            $this->updatedAuthorId($meta_data_data->author_id);
            $this->is_approve = $meta_data_data->status == 'approve' ? true : false;
        }

        if ($meta_data_session = $this->metaDataSession) {
            $this->is_update = true;

            $this->meta_data_id = $meta_data_session->id;
            $this->title = $meta_data_session->title;
            $this->author_id = $meta_data_session->author_id;
            $this->status = $meta_data_session->status;
            $this->visibility = $meta_data_session->visibility;
            $this->updatedAuthorId($meta_data_session->author_id);
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
        Session::forget('meta_data');

        $this->is_update = false;

        $this->dispatch('refresh-meta-data-session');

        $this->resetInput();
    }

    #[Computed()]
    public function metaDataSession()
    {
        try {
            if (Session::has('meta_data')) {
                $meta_data_session = Session::get('meta_data');
                if ($this->metaDataRepository->findById($meta_data_session->id)) {
                    return $meta_data_session;
                }
            }
        } catch (Throwable $th) {
            $this->createNewForm();
            return false;
        }
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

    public function updatedAuthorId($value)
    {
        $author_data = $this->authorRepository->findById($value, ['user']);

        $this->keyword = $author_data->nim . ' - ' . $author_data->name;
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

    #[Computed()]
    public function authors()
    {
        return $this->authorRepository->findByNameOrNim($this->keyword);
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

        try {
            $create_meta_data_data = CreateMetadataData::from($validated);

            $meta_data_data = $this->metaDataRepository->create($create_meta_data_data);

            $this->meta_data_id = $meta_data_data->id;

            session()->put('meta_data', $meta_data_data);

            $this->is_update = true;

            $this->dispatch('refresh-meta-data-session');

            session()->flash('meta-data-success', 'The meta data was successfully created.');
        } catch (Throwable $th) {
            session()->flash('meta-data-failed', $th->getMessage());
        }
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

        try {
            $update_meta_data_data = UpdateMetaData::from($validated);

            $meta_data_data = $this->metaDataRepository->update(
                $this->meta_data_id,
                $update_meta_data_data
            );

            if (!$this->is_update) {
                Session::put('meta_data', $meta_data_data);
            }

            session()->flash('meta-data-success', 'The meta data was successfully updated.');

            if ($this->is_update) {
                return redirect()->route('repository.edit', ['meta_data_slug' => $meta_data_data->slug]);
            }
        } catch (Throwable $th) {
            session()->flash('meta-data-failed', $th->getMessage());
        }
    }

    public function resetInput()
    {
        $this->title = '';
        $this->author_id = '';
        $this->status = '';
        $this->visibility = '';
        $this->keyword = '';

        $this->resetErrorBag();
    }

    public function render()
    {
        return view('livewire.repository.form.meta-data');
    }
}
