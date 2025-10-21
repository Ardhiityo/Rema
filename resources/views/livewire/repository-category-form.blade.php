<div class="card">
    <div class="card-header">
        <h4 class="card-title d-flex justify-content-between">
            <span>
                {{ $this->title }}
            </span>
            <span>
                Step 2/2
            </span>
        </h4>
        @if (session()->has('repository-success'))
            <div class="mt-4 alert-success alert">
                {{ session('repository-success') }}
            </div>
        @endif
    </div>
    <div class="card-body">
        <div class="mb-4 row">
            <div class="form-group">
                {{-- Category --}}
                <div>
                    <div class="input-group">
                        <label class="input-group-text" for="category_id" class="form-label">
                            Category
                            <sup class="ms-1">*</sup>
                        </label>
                        <select class="form-select" id="category_id" wire:model='category_id'>
                            <option value="">
                                Choose...
                            </option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @error('category_id')
                        <span class="badge bg-danger">
                            <small>{{ $message }}</small>
                        </span>
                    @enderror
                </div>
                {{-- Category --}}

                {{-- File Path --}}
                <div class="mt-4">
                    <label for="file_path" class="form-label">
                        File
                        @if (!$is_update)
                            <sup>*</sup>
                        @endif
                    </label>
                    <input class="form-control" wire:model='file_path' type="file" id="file_path"
                        accept="application/pdf">
                    @error('file_path')
                        <span class="badge bg-danger">
                            <small>{{ $message }}</small>
                        </span>
                    @enderror
                </div>
                {{-- File Path --}}
            </div>
        </div>

        {{-- Display Medum ++ only --}}
        <div class="gap-3 d-md-flex d-none">
            @if ($is_update)
                <button wire:click='updateRepository' wire:loading.attr='disabled' class="btn btn-primary"
                    wire:target='updateRepository'>
                    <span wire:target='updateRepository' wire:loading.class='d-none'>Update</span>
                    <span wire:loading wire:target='updateRepository'>
                        <span class="spinner-border spinner-border-sm text-light" role="status"></span>
                    </span>
                </button>
            @else
                <button wire:click='createRepository' wire:loading.attr='disabled' class="btn btn-primary"
                    wire:target='createRepository'>
                    <span wire:target='createRepository' wire:loading.class='d-none'>Add</span>
                    <span wire:loading wire:target='createRepository'>
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
        {{-- Display Medum ++ only --}}

        {{-- Display Small only --}}
        <div class="gap-3 d-md-none d-flex">
            @if ($is_update)
                <button wire:click='updateRepository' wire:loading.attr='disabled' class="btn btn-primary btn-sm"
                    wire:target='updateRepository'>
                    <span wire:loading.class='d-none' wire:target='updateRepository'>Update</span>
                    <span wire:loading wire:target='updateRepository'>
                        <span class="spinner-border spinner-border-sm text-light" role="status"></span>
                    </span>
                </button>
            @else
                <button wire:click='createRepository' wire:loading.attr='disabled' class="btn btn-primary btn-sm"
                    wire:target='createRepository'>
                    <span wire:loading.class='d-none' wire:target='createRepository'>Add</span>
                    <span wire:loading wire:target='createRepository'>
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
        {{-- Display Small only --}}
    </div>
</div>
