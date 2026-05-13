<div class="card">
    <div class="card-header">
        <h4 class="card-title">{{ $this->formTitle }}</h4>
    </div>
    <div class="card-body">
        @if (session()->has('faculty-success'))
            <div class="alert-success alert">
                {{ session('faculty-success') }}
            </div>
        @endif
        @if (session()->has('faculty-failed'))
            <div class="alert-danger alert">
                {{ session('faculty-failed') }}
            </div>
        @endif
        <div class="mb-4 row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="name" class="form-label">Faculty <sup>*</sup></label>
                    <input type="text" required class="form-control" id="name" wire:model='name'
                        placeholder="ex: Fakultas Ilmu Komputer">
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
                    <span wire:loading.class='d-none' wire:target='create'>Add</span>
                    <span wire:loading wire:target='create'>
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
    </div>
</div>
