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
                        <li class="breadcrumb-item"><a href="{{ route('repository.index') }}">Repositories</a></li>
                        <li class="breadcrumb-item"><a href="{{ url()->current() }}">Form</a></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    {{-- Meta Data Form --}}
    <div class="card">
        <div class="card-header">
            <h4 class="card-title d-flex justify-content-between">
                <span>
                    {{ $this->metaDataTitle() }}
                </span>
                @if (!$is_update)
                    <span>
                        Step 1/2
                    </span>
                @endif
            </h4>
        </div>
        <div class="card-body">
            @if (session()->has('succes-meta-data'))
                <div class="alert-success alert">
                    {{ session('succes-meta-data') }}
                </div>
            @endif
            <div class="mb-4 row">
                <div class="form-group">
                    {{-- Title --}}
                    <div>
                        <label for="basicInput" class="form-label">Title</label>
                        <input type="text" required class="form-control" id="basicInput" wire:model='title'
                            placeholder="ex: Sistem Informasi Management Sekolah"
                            {{ $this->islockForm ? 'disabled' : '' }}>
                        @error('slug')
                            <span class="badge bg-danger">
                                <small>{{ $message }}</small>
                            </span>
                        @enderror
                    </div>
                    {{-- Title --}}

                    {{-- Abstract --}}
                    <div class="mt-4">
                        <div class="mb-3 form-group">
                            <label for="exampleFormControlTextarea1" class="form-label">Abstract</label>
                            <textarea class="form-control" id="exampleFormControlTextarea1" wire:model='abstract' rows="3"
                                {{ $this->islockForm ? 'disabled' : '' }}></textarea>
                            @error('abstract')
                                <span class="badge bg-danger">
                                    <small>{{ $message }}</small>
                                </span>
                            @enderror
                        </div>
                    </div>
                    {{-- Abstract --}}

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
                                        <option value="{{ $author->author_id }}">{{ $author->nim }} - {{ $author->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('author_id')
                                <span class="badge bg-danger">
                                    <small>{{ $message }}</small>
                                </span>
                            @enderror
                        </div>
                    @endhasrole
                    {{-- Author --}}

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
                                    <small>{{ $message }}</small>
                                </span>
                            @enderror
                        </div>
                    @endhasrole
                    {{-- Status --}}

                    {{-- Visibility --}}
                    @hasrole('admin')
                        <div class="mt-4">
                            <div class="input-group">
                                <label class="input-group-text" for="status" class="form-label">
                                    Visibility
                                </label>
                                <select class="form-select" id="status" wire:model='visibility'>
                                    <option value="">
                                        Choose...
                                    </option>
                                    <option value="private">
                                        Private
                                    </option>
                                    <option value="protected">
                                        Protected
                                    </option>
                                    <option value="public">
                                        Public
                                    </option>
                                </select>
                            </div>
                            @error('visibility')
                                <span class="badge bg-danger">
                                    <small>{{ $message }}</small>
                                </span>
                            @enderror
                        </div>
                    @endhasrole
                    {{-- Visibility --}}
                </div>
            </div>
            @if ($this->isMetaDataEdit)
                <div class="gap-3 d-flex">
                    <button wire:click='updateMetaData' wire:loading.attr='disabled' class="btn btn-primary"
                        wire:target='updateMetaData'>
                        Update
                        <span wire:loading wire:target='updateMetaData'>
                            <span class="spinner-border spinner-border-sm text-light" role="status"></span>
                        </span>
                    </button>
                    <button wire:click='resetInputMetaData' class="btn btn-warning">
                        Clear
                    </button>
                    @if (!$is_update)
                        <button wire:click='createNewForm' wire:loading.attr='disabled' class="btn btn-danger"
                            wire:target='createNewForm'>
                            New Form
                            <span wire:loading wire:target='createNewForm'>
                                <span class="spinner-border spinner-border-sm text-light" role="status"></span>
                            </span>
                        </button>
                    @endif
                </div>
            @else
                <div class="gap-3 d-flex">
                    @if ($is_update)
                        @if (!$this->islockForm)
                            <button wire:click='updateMetaData' wire:loading.attr='disabled' class="btn btn-primary"
                                wire:target='updateMetaData'>
                                Update
                                <span wire:loading wire:target='updateMetaData'>
                                    <span class="spinner-border spinner-border-sm text-light" role="status"></span>
                                </span>
                            </button>
                            <button wire:click='resetInputMetaData' class="btn btn-warning">
                                Clear
                            </button>
                        @endif
                    @else
                        <button wire:click='createMetaData' wire:loading.attr='disabled' class="btn btn-primary"
                            wire:target='createMetaData'>
                            Save
                            <span wire:loading wire:target='createMetaData'>
                                <span class="spinner-border spinner-border-sm text-light" role="status"></span>
                            </span>
                        </button>
                        <button wire:click='resetInputMetaData' class="btn btn-warning">
                            Clear
                        </button>
                    @endif
                </div>
            @endif
        </div>
    </div>
    {{-- Meta Data Form --}}

    {{-- Repository Form --}}
    @if ($this->showRepositoryFrom)
        <div class="card">
            <div class="card-header">
                <h4 class="card-title d-flex justify-content-between">
                    <span>
                        {{ $this->repositoryTitle() }}
                    </span>
                    @if (!$is_update)
                        <span>
                            Step 2/2
                        </span>
                    @endif
                </h4>
            </div>
            <div class="card-body">
                <div class="mb-4 row">
                    <div class="form-group">
                        {{-- Category --}}
                        <div>
                            <div class="input-group">
                                <label class="input-group-text" for="category_id" class="form-label">
                                    Category
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
                    @if ($is_edit_repository)
                        <button wire:click='updateRepository' wire:loading.attr='disabled' class="btn btn-primary"
                            wire:target='updateRepository'>
                            Update
                            <span wire:loading wire:target='updateRepository'>
                                <span class="spinner-border spinner-border-sm text-light" role="status"></span>
                            </span>
                        </button>
                    @else
                        <button wire:click='createRepository' wire:loading.attr='disabled' class="btn btn-primary"
                            wire:target='createRepository'>
                            Add
                            <span wire:loading wire:target='createRepository'>
                                <span class="spinner-border spinner-border-sm text-light" role="status"></span>
                            </span>
                        </button>
                    @endif
                    <button wire:click='resetInputRepository' class="btn btn-warning">
                        Clear
                    </button>
                </div>
            </div>
        </div>
    @endif
    {{-- Repository Form --}}

    {{-- List --}}
    @if ($this->showRepositoriesList)
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">List of repositories</h4>
                @if (session()->has('repository-success'))
                    <div class="alert-success alert">
                        {{ session('repository-success') }}
                    </div>
                @endif
            </div>
            <div class="card-content">
                <div class="table-responsive">
                    <table class="table mb-0 text-center table-lg">
                        <thead>
                            <tr class="text-nowrap">
                                <th>No</th>
                                <th>Category</th>
                                <th>File</th>
                                @if (!$this->islockForm)
                                    <th>Action</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($this->repositories->categories as $key => $category)
                                <tr class="text-nowrap" wire:key='{{ $key }}'>
                                    <td class="text-bold-500">{{ $loop->iteration }}</td>
                                    <td class="text-bold-500">{{ $category->name }}</td>
                                    <td>
                                        <a href="{{ route('repository.read', [
                                            'category_slug' => $category->slug,
                                            'meta_data_slug' => $this->repositories->slug,
                                        ]) }}"
                                            class="btn btn-info" target="_blank">
                                            <i class="bi bi-eye-fill"></i>
                                        </a>
                                    </td>
                                    @if (!$this->islockForm)
                                        <td>
                                            <div class="gap-3 d-flex justify-content-center align-items-center">
                                                <button class="btn btn-warning"
                                                    wire:click="editRepository('{{ $this->repositories->slug }}', '{{ $category->slug }}')">
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>
                                                <button type="button" class="block btn btn-danger"
                                                    wire:click="deleteConfirmRepository('{{ $this->repositories->slug }}', '{{ $category->slug }}')"
                                                    data-bs-toggle="modal" data-bs-target="#border-less">
                                                    <i class="bi bi-trash3"></i>
                                                </button>
                                            </div>
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4">Data Not Found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!--BorderLess Modal Modal -->
            <div wire:ignore.self class="text-left modal fade modal-borderless" id="border-less" tabindex="-1"
                role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Confirm deletion</h5>
                        </div>
                        <div class="modal-body">
                            <p>Are you sure you want to delete the data?</p>
                        </div>
                        <div class="gap-2 modal-footer d-flex">
                            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">
                                <i class="bx bx-x d-block d-sm-none"></i>
                                <span class="d-none d-sm-block">Close</span>
                            </button>
                            <button type="button" class="btn btn-danger ms-1" wire:click="deleteRepository"
                                data-bs-dismiss="modal">
                                <i class="bx bx-check d-block d-sm-none"></i>
                                <span class="d-none d-sm-block">Accept</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    {{-- List --}}
</div>
