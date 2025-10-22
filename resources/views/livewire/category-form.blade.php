<div class="card">
    <div class="card-header">
        <h4 class="card-title">{{ $this->formTitle }}</h4>
    </div>
    <div class="card-body">
        @if (session()->has('category-success'))
            <div class="alert-success alert">
                {{ session('category-success') }}
            </div>
        @endif
        @if (session()->has('category-failed'))
            <div class="alert-danger alert">
                {{ session('category-failed') }}
            </div>
        @endif
        <div class="mb-4 row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="category" class="form-label">Category
                        <sup>*</sup>
                    </label>
                    <input type="text" required class="form-control" id="category" wire:model='name'
                        placeholder="ex: Skripsi">
                    @error('slug')
                        <span class="badge bg-danger">
                            <small>{{ $message }}</small>
                        </span>
                    @enderror
                </div>
            </div>
        </div>
        <div class="gap-3 d-flex">
            @if ($is_update)
                <button wire:click='update' wire:loading.attr='disabled' wire:target='update' class="btn btn-primary">
                    <span wire:target='update' wire:loading.class='d-none'>Update</span>
                    <span wire:loading wire:target='update'>
                        <span class="spinner-border spinner-border-sm text-light" role="status"></span>
                    </span>
                </button>
            @else
                <button wire:click='create' wire:loading.attr='disabled' wire:target='create' class="btn btn-primary">
                    <span wire:target='create' wire:loading.class='d-none'>Create</span>
                    <span wire:loading wire:target='create'>
                        <span class="spinner-border spinner-border-sm text-light" role="status"></span>
                    </span>
                </button>
            @endif
            <button wire:click='resetInput' wire:target='resetInput' class="btn btn-warning">
                <span wire:target='resetInput' wire:loading.class='d-none'> Clear</span>
                <span wire:loading wire:target='resetInput'>
                    <span class="spinner-border spinner-border-sm text-primary" role="status"></span>
                </span>
            </button>
        </div>
    </div>
</div>
