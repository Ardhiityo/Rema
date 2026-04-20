<?php

namespace App\Livewire\Repository\Form;

use App\Data\Keyword\CreateKeywordData;
use App\Data\Keyword\UpdateKeywordData;
use App\Repositories\Contratcs\KeywordRepositoryInterface;
use App\Repositories\Contratcs\MetaDataRepositoryInterface;
use App\Rules\MetDataKeywordFormRule;
use Exception;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class MetaDataKeywordForm extends Component
{
    // Start Form
    public string $name = '';
    
    public string $slug = '';
    // End Form
    
    public ?int $meta_data_id = null;
    
    public ?int $keyword_id = null;

    public bool $is_update = false;

    public function mount($meta_data_id)
    {
        if ($this->metaDataSession) {
            $this->meta_data_id = $this->metaDataSession->id;
        }

        if ($meta_data_id) {
            $this->meta_data_id = $meta_data_id;
        }
    }

    public function rules()
    {
        return [
            'meta_data_id' => ['required', 'exists:meta_data,id'],
            'name' => ['required', 'string'],
            'slug' => ['required', 'string', 'min:3', 'max:12', new MetDataKeywordFormRule($this->is_update ,$this->meta_data_id, $this->keyword_id)],
        ];
    }

    protected function validationAttributes()
    {
        return [
            'slug' => 'name',
        ];
    }

    #[Computed()]
    public function metaDataRepository()
    {
        return app(MetaDataRepositoryInterface::class);
    }

    #[Computed()]
    public function keywordRepository()
    {
        return app(KeywordRepositoryInterface::class);
    }

    #[Computed()]
    public function title()
    {
        return $this->is_update ? 'Edit Keyword' : 'Create Keyword';
    }

    #[Computed()]
    public function metaDataSession()
    {
        if (Session::has('meta_data')) {

            $meta_data_session = Session::get('meta_data');

            if ($this->metaDataRepository->findById($meta_data_session->id)) {
                return $meta_data_session;
            }
        }

        return false;
    }

    public function create()
    {
        $this->slug = Str::slug($this->name);

        $validated = $this->validate();

        try {
            $create_keyword_data = CreateKeywordData::from($validated);

            $this->keywordRepository->create($create_keyword_data);

            Session::flash('meta-data-keyword-success', 'Meta data keyword created successfully.');

            $this->dispatch('refresh-meta-data-keyword');

            $this->resetInput();
        } catch (Exception $exception) {
            Session::flash('meta-data-keyword-failed', $exception->getMessage());
        }
    }

    #[On('edit-meta-data-keyword')]
    public function edit($keyword_slug)
    {
        try {
            $this->resetInput();

            $meta_data_keyword_data = $this->keywordRepository
            ->findByMetaDataIdAndKeywordSlug($this->meta_data_id, $keyword_slug);

            $this->keyword_id = $meta_data_keyword_data->id;
            $this->name = $meta_data_keyword_data->name;
            
            $this->is_update = true;
        } catch (Exception $exception) {
            return session()->flash('meta-data-keyword-failed', $exception->getMessage());
        }
    }

    public function update()
    {
        $this->slug = Str::slug($this->name);
        
        $validated = $this->validate();

        try {
            $update_keyword_data = UpdateKeywordData::from($validated);

            $this->keywordRepository->update($this->keyword_id, $update_keyword_data);

            $this->resetInput();

            $this->is_update = false;

            session()->flash('meta-data-keyword-success', 'Meta data keyword updated successfully.');

            $this->dispatch('refresh-meta-data-keyword');
        } catch (Exception $exception) {
            session()->flash('meta-data-keyword-failed', $exception->getMessage());
        }
    }

    
    #[On('delete-confirm-meta-data-keyword')]
    public function deleteConfirm($meta_data_id, $keyword_slug)
    {
        try {
            $keyword_data = $this->keywordRepository
                ->findByMetaDataIdAndKeywordSlug($meta_data_id, $keyword_slug);

            $this->keyword_id = $keyword_data->id;
        } catch (Exception $exception) {
            session()->flash('meta-data-keyword-failed', $exception->getMessage());
        }
    }

    #[On('delete-meta-data-keyword')]
    public function delete()
    {
        try {
            $this->keywordRepository->delete($this->keyword_id);

            $this->resetInput();

            session()->flash('meta-data-keyword-success', 'Meta data keyword deleted successfully.');

            $this->dispatch('refresh-meta-data-keyword');
        } catch (Exception $exception) {
            session()->flash('meta-data-keyword-failed', $exception->getMessage());
        }
    }
    
    public function resetInput()
    {
        $this->name = '';
        $this->is_update = false;
        
        $this->resetErrorBag();
    }

    public function render()
    {
        return view('livewire.repository.form.meta-data-keyword');
    }
}
