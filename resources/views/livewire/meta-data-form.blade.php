    <div class="card">
        <div class="card-header">
            <h4 class="card-title d-flex justify-content-between">
                <span>
                    {{ $this->metaDataTitle }}
                </span>
                <span>
                    Step 1/2
                </span>
            </h4>
            @if (session()->has('succes-meta-data'))
                <div class="mt-4 alert-success alert">
                    {{ session('succes-meta-data') }}
                </div>
            @endif
        </div>
        <div class="card-body">
            <div class="mb-4 row">
                <div class="form-group">
                    {{-- Title --}}
                    <div>
                        <label for="title" class="form-label">Title</label>
                        <input type="text" required class="form-control" id="title" wire:model='title'
                            placeholder="ex: Sistem Informasi Repository" {{ $this->islockForm ? 'disabled' : '' }}>
                        @error('slug')
                            <span class="badge bg-danger text-wrap">
                                <small>{{ $message }}</small>
                            </span>
                        @enderror
                    </div>
                    {{-- Title --}}

                    {{-- Abstract --}}
                    <div class="mt-4">
                        <div class="mb-3 form-group">
                            <label for="abstract" class="form-label">Abstract</label>
                            <textarea class="form-control" id="abstract" wire:model='abstract' rows="3"
                                {{ $this->islockForm ? 'disabled' : '' }}></textarea>
                            @error('abstract')
                                <span class="badge bg-danger text-wrap">
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
                                        <option value="{{ $author->id }}">{{ $author->nim }} - {{ $author->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('author_id')
                                <span class="badge bg-danger text-wrap">
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
                                <span class="badge bg-danger text-wrap">
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
                                <span class="badge bg-danger text-wrap">
                                    <small>{{ $message }}</small>
                                </span>
                            @enderror
                        </div>
                    @endhasrole
                    {{-- Visibility --}}
                </div>
            </div>
            @if ($this->is_update)
                {{-- Display Medium ++ only --}}
                <div class="gap-3 align-items-center d-none d-md-flex">
                    <button wire:click='updateMetaData' wire:loading.attr='disabled' class="btn btn-primary"
                        wire:target='updateMetaData'>
                        Update
                        <span wire:loading wire:target='updateMetaData'>
                            <span class="spinner-border spinner-border-sm text-light" role="status"></span>
                        </span>
                    </button>
                    <button wire:click='resetInput' class="btn btn-warning">
                        Clear
                    </button>
                    @if ($this->metaDataSession)
                        <button wire:click='createNewForm' wire:loading.attr='disabled' class="btn btn-danger"
                            wire:target='createNewForm'>
                            New
                            <span wire:loading wire:target='createNewForm'>
                                <span class="spinner-border spinner-border-sm text-light" role="status"></span>
                            </span>
                        </button>
                    @endif
                </div>
                {{-- Display Medium ++ only --}}

                {{-- Display Small only --}}
                <div class="gap-3 align-items-centere d-flex d-md-none">
                    <button wire:click='updateMetaData' wire:loading.attr='disabled' class="btn btn-sm btn-primary"
                        wire:target='updateMetaData'>
                        Update
                        <span wire:loading wire:target='updateMetaData'>
                            <span class="spinner-border spinner-border-sm text-light" role="status"></span>
                        </span>
                    </button>
                    <button wire:click='resetInput' class="btn btn-warning btn-sm">
                        Clear
                    </button>
                    @if (!$is_update)
                        <button wire:click='createNewForm' wire:loading.attr='disabled' class="btn btn-danger btn-sm"
                            wire:target='createNewForm'>
                            New Form
                            <span wire:loading wire:target='createNewForm'>
                                <span class="spinner-border spinner-border-sm text-light" role="status"></span>
                            </span>
                        </button>
                    @endif
                </div>
                {{-- Display Small only --}}
            @else
                {{-- Display Medium ++ only --}}
                <div class="gap-3 d-none d-md-flex">
                    @if ($is_update)
                        @if (!$this->islockForm)
                            <button wire:click='updateMetaData' wire:loading.attr='disabled' class="btn btn-primary"
                                wire:target='updateMetaData'>
                                Update
                                <span wire:loading wire:target='updateMetaData'>
                                    <span class="spinner-border spinner-border-sm text-light" role="status"></span>
                                </span>
                            </button>
                            <button wire:click='resetInput' class="btn btn-warning">
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
                        <button wire:click='resetInput' class="btn btn-warning">
                            Clear
                        </button>
                    @endif
                </div>
                {{-- Display Medium ++ only --}}

                {{-- Display Small only --}}
                <div class="gap-3 d-flex d-md-none">
                    @if ($is_update)
                        @if (!$this->islockForm)
                            <button wire:click='updateMetaData' wire:loading.attr='disabled'
                                class="btn btn-primary btn-sm" wire:target='updateMetaData'>
                                Update
                                <span wire:loading wire:target='updateMetaData'>
                                    <span class="spinner-border spinner-border-sm text-light" role="status"></span>
                                </span>
                            </button>
                            <button wire:click='resetInput' class="btn btn-warning btn-sm">
                                Clear
                            </button>
                        @endif
                    @else
                        <button wire:click='createMetaData' wire:loading.attr='disabled'
                            class="btn btn-primary btn-sm" wire:target='createMetaData'>
                            Save
                            <span wire:loading wire:target='createMetaData'>
                                <span class="spinner-border spinner-border-sm text-light" role="status"></span>
                            </span>
                        </button>
                        <button wire:click='resetInput' class="btn btn-warning btn-sm">
                            Clear
                        </button>
                    @endif
                </div>
                {{-- Display Small only --}}
            @endif
        </div>
    </div>
