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

    <section class="section">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">{{ $this->formTitle }}</h4>
            </div>
            <div class="card-body">
                <div>
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
                                        <label class="input-group-text" for="inputGroupSelect01">
                                            Author
                                        </label>
                                        <select class="form-select" id="inputGroupSelect01" wire:model='author_id'>
                                            <option selected>Choose...</option>
                                            @foreach ($authors as $author)
                                                <option value="{{ $author->id }}">
                                                    {{ $author->name }}
                                                </option>
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

                            {{-- Type --}}
                            <div class="mt-4">
                                <div class="input-group">
                                    <label class="input-group-text" for="inputGroupSelect01">
                                        Type
                                    </label>
                                    <select class="form-select" id="inputGroupSelect01" wire:model='type'>
                                        <option selected>Choose...</option>
                                        <option value="thesis">
                                            Skripsi
                                        </option>
                                        <option value="final_project">
                                            Tugas Akhir
                                        </option>
                                        <option value="manual_book">
                                            Manual Book
                                        </option>
                                    </select>
                                </div>
                                @error('type')
                                    <span class="badge bg-danger">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                            {{-- Type --}}
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
    </section>
</div>
