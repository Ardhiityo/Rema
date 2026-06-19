<div class="card">
    <div class="card-header">
        <h4 class="card-title d-flex justify-content-between">
            <span>
                {{ $this->title }}
            </span>
            <span>
                <b>Step 3/3</b>
            </span>
        </h4>
        @if (session()->has('meta-data-category-success'))
            <div class="mt-4 alert alert-success" role="alert">
                {{ session('meta-data-category-success') }}
            </div>
        @endif
        @if (session()->has('meta-data-category-failed'))
            <div class="mt-4 alert alert-danger" role="alert">
                {!! session('meta-data-category-failed') !!}
            </div>
        @endif
    </div>
    <div class="card-body">
        <div class="mb-4 row">
            <div class="form-group">
                {{-- Category --}}
                <div>
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
                    </div>
                    @error('category_id')
                        <span class="badge bg-danger">
                            <small>{{ $message }}</small>
                        </span>
                    @enderror
                    <p class="mt-2 text-sm">
                        <b>Category file metadata must be unique</b>. If you have already created the same category file
                        metadata,
                        that category file metadata will be
                        <b>rejected</b>
                    </p>
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
                    <div class="mt-2 text-sm">
                        <p class="m-0">
                            The file must be in <b>PDF format</b>, and the maximum file size is <b>5 MB</b>.
                            <br>
                            <b>Notes:</b>
                            If your PDF file isn't supported, it's usually because your <b>PDF version isn't
                                supported by the system</b>. Please <b>convert your PDF to version 1.4</b> using the
                            following
                            website:
                        </p>
                        <ul>
                            <li>
                                <a href='https://www.pdf2go.com/convert-from-pdf' style='text-decoration:underline'
                                    target='_blank'>PDF2Go</a>
                            </li>
                            <li>
                                <a href='https://docupub.com/pdfconvert/' style='text-decoration:underline'
                                    target='_blank'>DocuPub</a>.
                            </li>
                        </ul>
                    </div>
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