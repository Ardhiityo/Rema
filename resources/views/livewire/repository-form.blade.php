<div>
    <x-page-title :title="'Repository Forms'" :content="'Form Repository data.'" />

    <livewire:meta-data-form :meta_data_id="$meta_data_id" />

    @if ($this->showMetaDataCategoryForm)
        <livewire:meta-data-category-form :meta_data_id="$meta_data_id" />
    @endif

    @if ($this->showMetaDataCategoryTable)
        <livewire:meta-data-category-table :meta_data_id="$meta_data_id" :is_approve="$is_approve" />
    @endif
</div>
