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
                <h4 class="card-title">Basic Inputs</h4>
            </div>
            <div class="card-body">
                <div>
                    <div class="mb-4 row">
                        <div class="form-group">
                            <div>
                                @if ($errors->has('title') || $errors->has('slug'))
                                    <div class="alert alert-dark">
                                        <ul class="mb-0">
                                            @error('title')
                                                <li>
                                                    <i class="bi bi-exclamation-triangle"></i>
                                                    {{ $message }}
                                                </li>
                                            @enderror
                                            @error('slug')
                                                <li>
                                                    <i class="bi bi-exclamation-triangle"></i>
                                                    {{ $message }}
                                                </li>
                                            @enderror
                                        </ul>
                                    </div>
                                @endif
                                <label for="basicInput" class="form-label">Title</label>
                                <input type="text" required class="form-control" id="basicInput" wire:model='title'
                                    placeholder="ex: Sistem Informasi Management Sekolah">
                            </div>

                            <div class="mt-4">
                                @error('abstract')
                                    <div class="alert alert-dark">
                                        <i class="bi bi-exclamation-triangle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                                <div class="mb-3 form-group">
                                    <label for="exampleFormControlTextarea1" class="form-label">Abstract</label>
                                    <textarea class="form-control" id="exampleFormControlTextarea1" wire:model='abstract' rows="3"></textarea>
                                </div>
                            </div>

                            <div class="mt-4">
                                @error('file_path')
                                    <div class="alert alert-dark">
                                        <i class="bi bi-exclamation-triangle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                                <label for="formFile" class="form-label">
                                    File
                                </label>
                                <input class="form-control" wire:model='file_path' type="file" id="formFile"
                                    accept="application/pdf">
                            </div>

                            <div class="mt-4">
                                @error('author_id')
                                    <div class="alert alert-dark">
                                        <i class="bi bi-exclamation-triangle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
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
                            </div>

                            <div class="mt-4">
                                @error('type')
                                    <div class="alert alert-dark">
                                        <i class="bi bi-exclamation-triangle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
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
                            </div>

                            <div class="mt-4">
                                @error('published_at')
                                    <div class="alert alert-dark">
                                        <i class="bi bi-exclamation-triangle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                                <label for="published_at" class="form-label">Published At</label>
                                <input type="date" class="mb-3 form-control" placeholder="Select date.."
                                    wire:model='published_at'>
                            </div>
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
