<div>
    <div class="page-title">
        <div class="row">
            <div class="order-last col-12 col-md-6 order-md-1">
                <h3>Repository Forms</h3>
                <p class="text-subtitle text-muted">Form Repository data.</p>
            </div>
            <div class="order-first col-12 col-md-6 order-md-2">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html">Repositories</a></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">{{ $this->formTitle }}</h4>
        </div>
        <div class="card-body">
            @if (session()->has('message'))
                <div class="alert-success alert">
                    {{ session('message') }}
                </div>
            @endif
            <div class="mb-4 row">
                <div class="form-group">
                    {{-- Title --}}
                    <div>
                        <label for="basicInput" class="form-label">Title</label>
                        <input type="text" required class="form-control" id="basicInput" wire:model='title'
                            placeholder="ex: Sistem Informasi Management Sekolah">
                        @error('slug')
                            <span class="badge bg-danger">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    {{-- Title --}}

                    {{-- Abstract --}}
                    <div class="mt-4">
                        <div class="mb-3 form-group">
                            <label for="exampleFormControlTextarea1" class="form-label">Abstract</label>
                            <textarea class="form-control" id="exampleFormControlTextarea1" wire:model='abstract' rows="3"></textarea>
                            @error('abstract')
                                <span class="badge bg-danger">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>
                    {{-- Abstract --}}

                    {{-- File Path --}}
                    <div class="mt-4">
                        <label for="formFile" class="form-label">
                            File
                        </label>
                        <input class="form-control" wire:model='file_path' type="file" id="formFile"
                            accept="application/pdf">
                        @error('file_path')
                            <span class="badge bg-danger">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    {{-- File Path --}}

                    {{-- Author --}}
                    @hasrole('admin')
                        <div class="mt-4">
                            <div class="input-group">
                                <label class="input-group-text" for="author_id">
                                    Author
                                </label>
                                <select class="form-select" id="author_id" wire:model='author_id'>
                                    <option value="">Choose...</option>
                                    @foreach ($authors as $author)
                                        <option value="{{ $author->author_id }}">{{ $author->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('author_id')
                                <span class="badge bg-danger">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    @endhasrole
                    {{-- Author --}}

                    {{-- Category --}}
                    <div class="mt-4">
                        <div class="input-group">
                            <label class="input-group-text" for="category_id">
                                Category
                            </label>
                            <select class="form-select" id="category_id" wire:model='category_id'>
                                <option selected value="">Choose...</option>
                                @foreach ($categories as $category)
                                    <option selected value="{{ $category->id }}">
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('category_id')
                            <span class="badge bg-danger">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    {{-- Category --}}

                    {{-- Status --}}
                    @hasrole('admin')
                        <div class="mt-4">
                            <div class="input-group">
                                <label class="input-group-text" for="status" class="form-label">
                                    Status
                                </label>
                                <select class="form-select" id="status" wire:model='status'>
                                    <option value="">
                                        Choose...
                                    </option>
                                    <option value="approve">
                                        Approve
                                    </option>
                                    <option value="revision">
                                        Revision
                                    </option>
                                    <option value="pending">
                                        Pending
                                    </option>
                                    <option value="reject">
                                        Reject
                                    </option>
                                </select>
                            </div>
                            @error('status')
                                <span class="badge bg-danger">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    @endhasrole
                    {{-- Status --}}
                </div>
            </div>
            <div class="gap-3 d-flex">
                @if ($is_update)
                    <button wire:click='update' wire:loading.attr='disabled' class="btn btn-primary"
                        wire:target='update'>
                        Update
                        <span wire:loading wire:target='update'>
                            <span class="spinner-border spinner-border-sm text-light" role="status"></span>
                        </span>
                    </button>
                @else
                    <button wire:click='create' wire:loading.attr='disabled' class="btn btn-primary"
                        wire:target='create'>
                        Add
                        <span wire:loading wire:target='create'>
                            <span class="spinner-border spinner-border-sm text-light" role="status"></span>
                        </span>
                    </button>
                @endif
                <button wire:click='resetInput' class="btn btn-warning">
                    Clear
                </button>
            </div>
        </div>
    </div>
</div>
