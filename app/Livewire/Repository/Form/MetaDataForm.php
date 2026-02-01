<?php

namespace App\Livewire\Repository\Form;

use Throwable;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Auth;
use App\Data\Metadata\UpdateMetaData;
use Illuminate\Support\Facades\Session;
use App\Data\Metadata\CreateMetadataData;
use App\Repositories\Contratcs\MetaDataRepositoryInterface;
use App\Repositories\Contratcs\StudyProgramRepositoryInterface;

class MetaDataForm extends Component
{
    // Start Form
    public string $title = '';
    public string|null $author_name = '';
    public string|null $author_nim = '';
    public string|null $study_program_id = '';
    public string $status = 'approve';
    public string $year = '';
    public string $visibility = 'public';
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
            $this->author_name = $meta_data_data->author_name;
            $this->author_nim = $meta_data_data->author_nim;
            $this->study_program_id = $meta_data_data->study_program_id;
            $this->year = $meta_data_data->year;
            $this->status = $meta_data_data->status;
            $this->visibility = $meta_data_data->visibility;
            $this->is_approve = $meta_data_data->status == 'approve' ? true : false;
        }

        if ($meta_data_session = $this->metaDataSession) {
            $this->is_update = true;

            $this->meta_data_id = $meta_data_session->id;
            $this->title = $meta_data_session->title;
            $this->author_name = $meta_data_session->author_name;
            $this->author_nim = $meta_data_session->author_nim;
            $this->study_program_id = $meta_data_session->study_program_id;
            $this->status = $meta_data_session->status;
            $this->visibility = $meta_data_session->visibility;
        }

        if (is_null($meta_data_id)) {
            $this->year = now()->year;
            $user = Auth::user();
            if ($user->hasRole('author')) {
                $this->author_name = $user?->name;
                $this->author_nim = $user?->author?->nim;
                $this->study_program_id = $user?->author?->studyProgram?->id;
            }
        }
    }

    protected function validationAttributes()
    {
        return [
            'slug' => 'title',
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
            'author_name' => ['required', 'min:1', 'max:100'],
            'author_nim' => ['required', 'min:8', 'max:15'],
            'study_program_id' => ['required', 'exists:study_programs,id'],
            'year' => ['required', 'date_format:Y'],
            'status' => ['required', 'in:approve,process,reject,revision'],
            'visibility' => ['required', 'in:private,public']
        ];
    }

    #[Computed()]
    public function metaDataRepository()
    {
        return app(MetaDataRepositoryInterface::class);
    }

    public function create()
    {
        $user = Auth::user();

        if ($user->hasRole('author')) {
            $this->status = 'process';
            $this->visibility = 'private';
        }

        $this->slug = Str::slug($this->title);

        $validated = $this->validate();

        try {
            $create_meta_data_data = CreateMetadataData::from($validated);

            $meta_data_data = $this->metaDataRepository->create($create_meta_data_data);

            $this->meta_data_id = $meta_data_data->id;

            Session::put('meta_data', $meta_data_data);

            $this->is_update = true;

            $this->dispatch('refresh-meta-data-session');

            Session::flash('meta-data-success', 'The meta data was successfully created.');
        } catch (Throwable $th) {
            Session::flash('meta-data-failed', $th->getMessage());
        }
    }

    public function update()
    {
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

            Session::flash('meta-data-success', 'The meta data was successfully updated.');

            if ($this->is_update) {
                return redirect()->route('repository.edit', ['meta_data_slug' => $meta_data_data->slug]);
            }
        } catch (Throwable $th) {
            Session::flash('meta-data-failed', $th->getMessage());
        }
    }

    public function resetInput()
    {
        $this->title = '';
        $this->author_name = '';
        $this->author_nim = '';
        $this->study_program_id = '';
        $this->status = '';
        $this->visibility = '';

        $this->resetErrorBag();
    }

    public function render()
    {
        $study_programs = app(StudyProgramRepositoryInterface::class)->all();

        return view('livewire.repository.form.meta-data', compact('study_programs'));
    }
}
