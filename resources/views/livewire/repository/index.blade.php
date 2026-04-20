<div>
    <x-page-title :title="'Repository Forms'">
        You need to follow <b>3 steps</b> to set up the repository, follow each step as outlined in the guide.
    </x-page-title>

    <livewire:repository.form.meta-data-form :meta_data_id="$meta_data_id" wire:key="form-meta-data-{{ $meta_data_id }}" />

    @if ($this->showMetaDataKeywordForm)
        <livewire:repository.form.meta-data-keyword-form :meta_data_id="$meta_data_id" wire:key="form-keyword-{{ $meta_data_id }}" />
    @endif

    @if ($this->showMetaDataKeywordList)
        <livewire:repository.list.meta-data-keyword-list :meta_data_id="$meta_data_id" wire:key="list-keyword-{{ $meta_data_id }}" />
    @endif

    @if ($this->showMetaDataCategoryForm)
        <livewire:repository.form.meta-data-category-form :meta_data_id="$meta_data_id" wire:key="form-category-{{ $meta_data_id }}" />
    @endif

    @if ($this->showMetaDataCategoryList)
        <livewire:repository.list.meta-data-category-list :meta_data_id="$meta_data_id" wire:key="list-category-{{ $meta_data_id }}" />
    @endif
</div>