<div class="card">
    <div class="card-header">
        <h4 class="card-title d-flex justify-content-between">
            <span>
                {{ $this->title }}
            </span>
            <span>
                <b>Step 2/3</b>
            </span>
        </h4>
        @if (session()->has('meta-data-keyword-success'))
            <div class="mt-4 alert-success alert">
                {{ session('meta-data-keyword-success') }}
            </div>
        @endif
        @if (session()->has('meta-data-keyword-failed'))
            <div class="mt-4 alert-danger alert">
                {{ session('meta-data-keyword-failed') }}
            </div>
        @endif
    </div>
    <div class="card-body">
        <div class="mb-4 row">
            <div class="form-group">
                <label for="name" class="form-label">Name <sup>*</sup> </label>
                <input type="text" required class="form-control" id="name" wire:model='name'
                    placeholder="ex: Repository">
                @error('slug')
                    <span class="badge bg-danger text-wrap">
                        <small>{{ $message }}</small>
                    </span>
                @enderror
                <p class="mt-2 text-sm">
                    In addition to searching by title in the metadata, keywords are also used to search your repository.
                    <br> Please use up to <b>3 keywords</b> that are relevant to your repository. <br>
                    For example, if your metadata title is <b>"Sistem Informasi Repositori"</b>, then you have <b>3
                        keywords</b>:
                    <b>Sistem, Informasi, Repositori</b>
                </p>
            </div>
        </div>
        {{-- Display Medium ++ only --}}
        <div class="gap-3 d-flex">
            @if ($is_update)
                <button wire:click='update' wire:loading.attr='disabled' class="btn btn-primary" wire:target="update">
                    <span wire:loading.class='d-none' wire:target="update">Update</span>
                    <span wire:loading wire:target="update">
                        <span class="spinner-border spinner-border-sm text-light" role="status"></span>
                    </span>
                </button>
            @else
                <button wire:click='create' wire:loading.attr='disabled' wire:target="create" class="btn btn-primary">
                    <span wire:loading.class='d-none' wire:target="create">Add</span>
                    <span wire:loading wire:target="create">
                        <span class="spinner-border spinner-border-sm text-light" role="status"></span>
                    </span>
                </button>
            @endif
            <button wire:click='resetInput' class="btn btn-warning" wire:target='resetInput'
                wire:loading.attr='disabled'>
                <span wire:target='resetInput' wire:loading.class='d-none'>Clear</span>
                <span wire:loading wire:target='resetInput'>
                    <span class="spinner-border spinner-border-sm text-primary" role="status"></span>
                </span>
            </button>
        </div>
        {{-- Display Medium ++ only --}}
    </div>
</div>