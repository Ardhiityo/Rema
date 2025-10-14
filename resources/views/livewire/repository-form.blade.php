<div>
    <x-page-title :title="'Repository Forms'" :content="'Form Repository data.'" />

    <livewire:meta-data-form />

    @if ($this->showRepositoryCategoryFrom)
        <livewire:repository-category-form />
    @endif

    @if ($this->showRepositoriesList)
        <livewire:repository-table />
    @endif

</div>
