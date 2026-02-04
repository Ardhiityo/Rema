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
            <div class="mt-4 alert alert-success" role="alert">
                {{ session('repository-success') }}
            </div>
        @endif
        @if (session()->has('repository-failed'))
            <div class="mt-4 alert alert-danger" role="alert">
                {!! session('repository-failed') !!}
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
                        @if (! $is_update)
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

        <div class="gap-3 d-flex">
            @if ($is_update)
                <button wire:click='update' wire:loading.attr='disabled' class="btn btn-primary"
                    wire:target="update,file_path">
                    <span wire:loading.class='d-none' wire:target="update,file_path">Update</span>
                    <span wire:loading wire:target="update,file_path">
                        <span class="spinner-border spinner-border-sm text-light" role="status"></span>
                    </span>
                </button>
            @else
                <button wire:click='create' wire:loading.attr='disabled' wire:target="create,file_path"
                    class="btn btn-primary">
                    <span wire:loading.class='d-none' wire:target="create,file_path">Add</span>
                    <span wire:loading wire:target="create,file_path">
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