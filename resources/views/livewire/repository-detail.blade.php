<div>
    <div class="page-title">
        <div class="row">
            <div class="order-last col-12 col-md-6 order-md-1">
                <h3>Repositories</h3>
                <p class="text-subtitle text-muted">Detailed information about the metadata.</p>
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
        <div>
            <section class="section">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <div class="flex-column d-flex">
                                <h3 class="card-title">Metadata</h3>
                                <div class="gap-2 mt-3 d-flex flex-column">
                                    <h5>
                                        {{ $author }}
                                    </h5>
                                    <p>
                                        <small>{{ $nim }} | {{ $study_program }}</small>
                                    </p>
                                </div>
                            </div>
                            <p class="card-text">
                                {{ $title }}
                            </p>
                            <p>
                                <small> {{ $category }} - {{ $created_at }}</small>
                            </p>
                            <a href="{{ route('repository.read', ['repository' => $slug]) }}" target="_blank"
                                class="block btn btn-primary">
                                <i class="bi bi-eye-fill"></i>
                                View
                            </a>
                        </div>
                    </div>
                </div>
                <div class="page-title">
                    <div class="row">
                        <div class="order-last col-12 col-md-6 order-md-1">
                            <h3>Notes</h3>
                            <p class="text-subtitle text-muted">Detailed information about the notes .</p>
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
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="message" class="form-label">Message</label>
                                    <textarea class="form-control" id="message" wire:model='message' rows="3"></textarea>
                                    @error('message')
                                        <span class="badge bg-danger">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="gap-3 d-flex">
                            @if ($is_update)
                                <button wire:click='update' wire:loading.attr='disabled' wire:target='update'
                                    class="btn btn-primary">
                                    Update
                                    <span wire:loading wire:target='update'>
                                        <span class="spinner-border spinner-border-sm text-light" role="status"></span>
                                    </span>
                                </button>
                            @else
                                <button wire:click='create' wire:loading.attr='disabled' wire:target='create'
                                    class="btn btn-primary">
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
                <div class="card">
                    <div class="card-content">
                        @foreach ($notes as $note)
                            <div class="card-body">
                                <div class="flex-column d-flex">
                                    <h5 class="card-title">{{ $note->created_at }}</h5>
                                </div>
                                <p class="mt-3 card-text">
                                    {{ $note->message }}
                                </p>
                                @hasrole('admin')
                                    <div class="gap-3 d-flex">
                                        <button class="btn btn-primary" wire:click="edit('{{ $note->id }}')">
                                            <i class="bi bi-pencil-square"></i>
                                            Edit
                                        </button>
                                        <button target="_blank" class="btn btn-danger"
                                            wire:click="deleteConfirm('{{ $note->id }}')" data-bs-toggle="modal"
                                            data-bs-target="#border-less">
                                            <i class="bi bi-trash3"></i>
                                            Delete
                                        </button>
                                    </div>
                                @endhasrole
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        </div>
    </section>
    <!--BorderLess Modal Modal -->
    <div wire:ignore.self class="text-left modal fade modal-borderless" id="border-less" tabindex="-1" role="dialog"
        aria-labelledby="myModalLabel1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Border-Less</h5>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete the data?</p>
                </div>
                <div class="gap-2 modal-footer d-flex">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Close</span>
                    </button>
                    <button type="button" class="btn btn-danger ms-1" wire:click="delete" data-bs-dismiss="modal">
                        <i class="bx bx-check d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Accept</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
