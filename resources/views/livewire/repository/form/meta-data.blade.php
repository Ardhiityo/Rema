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

                    {{-- Author Name --}}
                    <div>
                        <label for="author_name" class="form-label">Author <sup>*</sup> </label>
                        <input type="text" required class="form-control" id="author_name" wire:model='author_name'
                            placeholder="ex: Arya Adhi Prasetyo">
                        @error('author_name')
                            <span class="badge bg-danger text-wrap">
                                <small>{{ $message }}</small>
                            </span>
                        @enderror
                    </div>
                    {{-- Author Name --}}

                    {{-- Author NIM --}}
                    <div class="mt-4">
                        <label for="author_nim" class="form-label">NIM <sup>*</sup> </label>
                        <input type="text" required class="form-control" id="author_nim" wire:model='author_nim'
                            placeholder="ex: 22040004">
                        @error('author_nim')
                            <span class="badge bg-danger text-wrap">
                                <small>{{ $message }}</small>
                            </span>
                        @enderror
                    </div>
                    {{-- Author NIM --}}

                    {{-- Title --}}
                    <div class="mt-4">
                        <label for="title" class="form-label">Title <sup>*</sup> </label>
                        <input type="text" required class="form-control" id="title" wire:model='title'
                            placeholder="ex: Sistem Informasi Repository">
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
                            placeholder="ex: 2025">
                        @error('year')
                            <span class="badge bg-danger text-wrap">
                                <small>{{ $message }}</small>
                            </span>
                        @enderror
                    </div>
                    {{-- Graduation Year --}}

                    {{-- Author Study Program --}}
                    <div class="mt-4">
                        <div class="input-group">
                            <label class="input-group-text" for="study_program_id" class="form-label">
                                Study Program <sup class="ms-1">*</sup>
                            </label>
                            <select class="form-select" id="study_program_id" wire:model='study_program_id'>
                                <option value="">
                                    Choose...
                                </option>
                                @foreach ($study_programs as $study_program)
                                    <option value="{{ $study_program->id }}">
                                        {{ $study_program->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('study_program_id')
                            <span class="badge bg-danger text-wrap">
                                <small>{{ $message }}</small>
                            </span>
                        @enderror
                    </div>
                    {{-- Author Study Program --}}

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
                                    <option value="process">
                                        Process
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
                        {{-- Status --}}

                        {{-- Visibility --}}
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
                    @if ($this->metaDataSession)
                        <button wire:click='update' wire:loading.attr='disabled' class="btn btn-primary"
                            wire:target='update'>
                            <span wire:target='update' wire:loading.class='d-none'>Update</span>
                            <span wire:loading wire:target='update'>
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
                        <button wire:click='createNewForm' wire:loading.attr='disabled' class="btn btn-danger"
                            wire:target='createNewForm'>
                            <span wire:target='createNewForm' wire:loading.class='d-none'>New</span>
                            <span wire:loading wire:target='createNewForm'>
                                <span class="spinner-border spinner-border-sm text-light" role="status"></span>
                            </span>
                        </button>
                    @else
                        <button wire:click='update' wire:loading.attr='disabled' class="btn btn-primary"
                            wire:target='update'>
                            <span wire:target='update' wire:loading.class='d-none'>Update</span>
                            <span wire:loading wire:target='update'>
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
            @else
                {{-- Display Medium ++ only --}}
                <div class="gap-3 align-items-center d-flex">
                    <button wire:click='create' wire:loading.attr='disabled' class="btn btn-primary"
                        wire:target='create'>
                        <span wire:loading.class='d-none' wire:target='create'>Save</span>
                        <span wire:loading wire:target='create'>
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
                </div>
                {{-- Display Medium ++ only --}}
            @endif
        </div>
    </div>
