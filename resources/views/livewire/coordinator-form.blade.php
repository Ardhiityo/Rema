<div class="card">
    <div class="card-header">
        <h4 class="card-title">{{ $this->formTitle }}</h4>
    </div>
    <div class="card-body">
        @if (session()->has('coordinator-success'))
            <div class="alert-success alert">
                {{ session('coordinator-success') }}
            </div>
        @endif
        @if (session()->has('coordinator-failed'))
            <div class="alert-danger alert">
                {{ session('coordinator-failed') }}
            </div>
        @endif
        <div class="mb-4 row">
            <div class="col-md-6">
                <div class="gap-3 flex-column d-flex">
                    {{-- NIM --}}
                    <div>
                        <label for="basicInput" class="form-label">
                            NIDN
                            <sup>*</sup>
                        </label>
                        <input type="text" required class="form-control" id="basicInput" wire:model='nidn'
                            placeholder="ex: 22040004" name="nidn">
                        @error('nidn')
                            <span class="badge bg-danger">
                                <small>{{ $message }}</small>
                            </span>
                        @enderror
                    </div>
                    {{-- NIM --}}

                    {{-- Coordinator --}}
                    <div>
                        <label for="name" class="form-label">
                            Coordinator
                            <sup>*</sup>
                        </label>
                        <input type="text" required class="form-control" id="name" wire:model='name'
                            placeholder="ex: Arya Adhi Prasetyo" name="name">
                        @error('name')
                            <span class="badge bg-danger">
                                <small>{{ $message }}</small>
                            </span>
                        @enderror
                    </div>
                    {{-- Coordinator --}}

                    {{-- Position --}}
                    <div>
                        <label for="position" class="form-label">
                            Position
                            <sup>*</sup>
                        </label>
                        <input type="text" required class="form-control" id="position" wire:model='position'
                            placeholder="ex: Ketua Program Studi Teknik Informatika" name="position">
                        @error('position')
                            <span class="badge bg-danger">
                                <small>{{ $message }}</small>
                            </span>
                        @enderror
                    </div>
                    {{-- Position --}}
                </div>
            </div>

            <div class="gap-3 mt-4 d-flex">
                @if ($is_update)
                    <button wire:click='update' wire:loading.attr='disabled' wire:target='update'
                        class="btn btn-primary">
                        <span wire:target='update' wire:loading.class='d-none'>Update</span>
                        <span wire:loading wire:target='update'>
                            <span class="spinner-border spinner-border-sm text-light" role="status"></span>
                        </span>
                    </button>
                @else
                    <button wire:click='create' wire:loading.attr='disabled' wire:target='create'
                        class="btn btn-primary">
                        <span wire:target='create' wire:loading.class='d-none'>Add</span>
                        <span wire:loading wire:target='create'>
                            <span class="spinner-border spinner-border-sm text-light" role="status"></span>
                        </span>
                    </button>
                @endif
                <button wire:click='resetInput' class="btn btn-warning" wire:loading.attr='disabled'
                    wire:target='resetInput'>
                    <span wire:target='resetInput' wire:loading.class='d-none'>Clear</span>
                    <span wire:loading wire:target='resetInput'>
                        <span class="spinner-border spinner-border-sm text-primary" role="status"></span>
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>
