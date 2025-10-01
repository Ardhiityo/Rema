<div>
    <div class="page-title">
        <div class="row">
            <div class="order-last col-12 col-md-6 order-md-1">
                <h3>Authors</h3>
                <p class="text-subtitle text-muted">All Authors data listed.</p>
            </div>
            <div class="order-first col-12 col-md-6 order-md-2">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html">Authors</a></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <section class="section">
        <section class="section">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ $this->formTitle }}</h4>
                </div>
                <div class="card-body">
                    <div>
                        <div class="mb-4 row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div>
                                        <label for="basicInput" class="form-label">NIM</label>
                                        <input type="text" required class="form-control" id="basicInput"
                                            wire:model='nim' placeholder="ex: 22040004" name="nim">
                                        @error('nim')
                                            <span class="badge bg-danger">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="mt-3">
                                        <label for="basicInput" class="form-label">Author</label>
                                        <input type="text" required class="form-control" id="basicInput"
                                            wire:model='name' placeholder="ex: Arya Adhi Prasetyo" name="name">
                                        @error('name')
                                            <span class="badge bg-danger">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="mt-4">
                                        <div class="input-group">
                                            <label class="input-group-text" for="inputGroupSelect01" class="form-label">
                                                Study Program
                                            </label>
                                            <select class="form-select" id="inputGroupSelect01"
                                                wire:model='study_program_id'>
                                                <option selected>Choose...</option>
                                                @foreach ($study_programs as $study_program)
                                                    <option value="{{ $study_program->id }}">
                                                        {{ $study_program->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('study_program_id')
                                            <span class="badge bg-danger">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
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

        <div class="card">
            <div class="card-header">
                <div class="gap-3 d-flex align-items-center">
                    <input type="text" wire:model.live.debounce.250ms='keyword' autofocus class="form-control"
                        id="basicInput" placeholder="Search...">
                    <button class="btn btn-primary" wire:click='resetInput'>
                        <i class="bi bi-arrow-clockwise"></i>
                    </button>
                </div>
            </div>
            <div class="card-content">
                <div class="table-responsive">
                    <table class="table mb-0 text-center table-lg">
                        <thead>
                            <tr class="text-nowrap">
                                <th>No</th>
                                <th>NIM</th>
                                <th>Name</th>
                                <th>Study Program</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($authors as $author)
                                <tr class="text-nowrap">
                                    <td class="text-bold-500">{{ $loop->index + $authors->firstItem() }}</td>
                                    <td class="text-bold-500">{{ $author->nim }}</td>
                                    <td class="text-bold-500">{{ $author->name }}</td>
                                    <td class="text-bold-500">{{ $author->study_program_name }}</td>
                                    <td class="gap-3 d-flex justify-content-center align-items-center">
                                        <button wire:click="edit('{{ $author->id }}')"
                                            wire:key="{{ $author->nim }}" class="btn btn-warning">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <button type="button" wire:click="deleteConfirm('{{ $author->id }}')"
                                            class="block btn btn-danger" data-bs-toggle="modal"
                                            data-bs-target="#border-less">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Data Not Found</td>
                                </tr>
                            @endforelse
                            <tr>
                        </tbody>
                    </table>
                </div>
                @if ($authors->isNotEmpty())
                    <div class="p-3 pt-4">
                        {{ $authors->links() }}
                    </div>
                @endif
            </div>
        </div>

        <!--BorderLess Modal Modal -->
        <div wire:ignore.self class="text-left modal fade modal-borderless" id="border-less" tabindex="-1"
            role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Border-Less</h5>
                        <button type="button" class="close rounded-pill" data-bs-dismiss="modal"
                            aria-label="Close">
                            <i data-feather="x"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete the {{ $name }} data?</p>
                    </div>
                    <div class="gap-2 modal-footer d-flex">
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">
                            <i class="bx bx-x d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Close</span>
                        </button>
                        <button type="button" class="btn btn-danger ms-1" wire:click='delete'
                            data-bs-dismiss="modal">
                            <i class="bx bx-check d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Accept</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
