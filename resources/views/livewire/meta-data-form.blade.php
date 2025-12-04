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
            @if (session()->has('meta-data-success'))
                <div class="mt-4 alert-success alert">
                    {{ session('meta-data-success') }}
                </div>
            @endif
            @if (session()->has('meta-data-failed'))
                <div class="mt-4 alert-danger alert">
                    {{ session('meta-data-failed') }}
                </div>
            @endif
        </div>
        <div class="card-body">
            <div class="mb-4 row">
                <div class="form-group">
                    {{-- Title --}}
                    <div>
                        <label for="title" class="form-label">Title <sup>*</sup> </label>
                        <input type="text" required class="form-control" id="title" wire:model='title'
                            placeholder="ex: Sistem Informasi Repository" {{ $this->islockForm ? 'disabled' : '' }}>
                        @error('slug')
                            <span class="badge bg-danger text-wrap">
                                <small>{{ $message }}</small>
                            </span>
                        @enderror
                    </div>
                    {{-- Title --}}

                    {{-- Graduation Year --}}
                    <div class="mt-4">
                        <label for="year" class="form-label">Year of Graduation <sup>*</sup> </label>
                        <input type="number" required class="form-control" id="year" wire:model='year'
                            placeholder="ex: 2025" {{ $this->islockForm ? 'disabled' : '' }}>
                        @error('year')
                            <span class="badge bg-danger text-wrap">
                                <small>{{ $message }}</small>
                            </span>
                        @enderror
                    </div>
                    {{-- Graduation Year --}}

                    {{-- Author --}}
                    @hasrole('admin')
                        <div class="mt-4">
                            <label for="keyword" class="form-label">Author's <sup>*</sup> </label>
                            <div class="position-relative">
                                <input type="text" class="form-control" id="keyword"
                                    wire:model.live.debounce.250ms='keyword' placeholder="Search..."
                                    {{ $this->islockForm ? 'disabled' : '' }}>
                                <span class="top-0 mt-2 me-2 position-absolute end-0" wire:loading wire:target='keyword'>
                                    <span class="spinner-border spinner-border-sm text-primary" role="status"></span>
                                </span>
                            </div>
                            @error('author_id')
                                <span class="badge bg-danger text-wrap">
                                    <small>{{ $message }}</small>
                                </span>
                            @enderror
                            @if ($this->authors->toCollection()->isNotEmpty())
                                <ul class="my-1 form-control">
                                    @foreach ($this->authors as $author)
                                        <li class="py-1 list-group-item">
                                            <input type="radio" name="author_id" value="{{ $author->id }}"
                                                id="{{ $author->id }}" class="d-none" wire:model.live='author_id'>
                                            <label for="{{ $author->id }}" class="w-100" style="cursor: pointer">
                                                {{ $author->nim }} - {{ $author->name }}
                                            </label>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    @endhasrole
                    {{-- Author --}}

                    {{-- Status --}}
                    @hasrole('admin')
                        <div class="mt-4">
                            <div class="input-group">
                                <label class="input-group-text" for="status" class="form-label">
                                    Status <sup class="ms-1">*</sup>
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
                                    Visibility <sup class="ms-1">*</sup>
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
                <div class="gap-3 align-items-center d-flex">
                    @if (!$this->islockForm)
                        <button wire:click='updateMetaData' wire:loading.attr='disabled' class="btn btn-primary"
                            wire:target='updateMetaData'>
                            <span wire:target='updateMetaData' wire:loading.class='d-none'>Update</span>
                            <span wire:loading wire:target='updateMetaData'>
                                <span class="spinner-border spinner-border-sm text-light" role="status"></span>
                            </span>
                        </button>
                        <button wire:click='resetInput' wire:target='resetInput' wire:click.attr='disabled'
                            class="btn btn-warning">
                            <span wire:target='resetInput' wire:loading.class='d-none'>Clear</span>
                            <span wire:loading wire:target='resetInput'>
                                <span class="spinner-border spinner-border-sm text-primary" role="status"></span>
                            </span>
                        </button>
                    @endif
                    @if ($this->metaDataSession)
                        <button wire:click='createNewForm' wire:loading.attr='disabled' class="btn btn-danger"
                            wire:target='createNewForm'>
                            <span wire:target='createNewForm' wire:loading.class='d-none'>New</span>
                            <span wire:loading wire:target='createNewForm'>
                                <span class="spinner-border spinner-border-sm text-light" role="status"></span>
                            </span>
                        </button>
                    @endif
                </div>
                {{-- Display Medium ++ only --}}
            @else
                {{-- Display Medium ++ only --}}
                <div class="gap-3 d-flex">
                    @if ($is_update)
                        @if (!$this->islockForm)
                            <button wire:click='updateMetaData' wire:loading.attr='disabled' class="btn btn-primary"
                                wire:target='updateMetaData'>
                                <span wire:target='updateMetaData' wire:loading.class='d-none'>Update</span>
                                <span wire:loading wire:target='updateMetaData'>
                                    <span class="spinner-border spinner-border-sm text-light" role="status"></span>
                                </span>
                            </button>
                            <button wire:click='resetInput' wire:target='resetInput' wire:click.attr='disabled'
                                class="btn btn-warning">
                                <span wire:target='resetInput' wire:loading.class='d-none'>Clear</span>
                                <span wire:loading wire:target='resetInput'>
                                    <span class="spinner-border spinner-border-sm text-primary" role="status"></span>
                                </span>
                            </button>
                        @endif
                    @else
                        <button wire:click='createMetaData' wire:loading.attr='disabled' class="btn btn-primary"
                            wire:target='createMetaData'>
                            <span wire:loading.class='d-none' wire:target='createMetaData'>Save</span>
                            <span wire:loading wire:target='createMetaData'>
                                <span class="spinner-border spinner-border-sm text-light" role="status"></span>
                            </span>
                        </button>
                        <button wire:click='resetInput' wire:target='resetInput' wire:click.attr='disabled'
                            class="btn btn-warning">
                            <span wire:target='resetInput' wire:loading.class='d-none'>Clear</span>
                            <span wire:loading wire:target='resetInput'>
                                <span class="spinner-border spinner-border-sm text-primary" role="status"></span>
                            </span>
                        </button>
                    @endif
                </div>
                {{-- Display Medium ++ only --}}
            @endif
        </div>
    </div>
