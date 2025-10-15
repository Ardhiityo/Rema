<div>
    <x-page-title :title="'Repository Forms'" :content="'Form Repository data.'" />

    <livewire:meta-data-form />

    @if ($this->showRepositoryCategoryFrom)
        <livewire:repository-category-form />
    @endif

    @if ($this->showRepositoriesTable)
        <livewire:repository-table :meta_data_id="$meta_data_id" :is_approve="$is_approve" />
    @endif

</div>
