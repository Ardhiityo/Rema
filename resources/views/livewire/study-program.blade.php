<div>
    <div class="page-title">
        <div class="row">
            <div class="order-last col-12 col-md-6 order-md-1">
                <h3>Study Programs</h3>
                <p class="text-subtitle text-muted">Give textual form controls like input upgrade with
                    custom styles,
                    sizing, focus states, and more.</p>
            </div>
            <div class="order-first col-12 col-md-6 order-md-2">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html">Study Program</a></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <section class="section">
        <section>
            <div>
                <section class="section">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Basic Inputs</h4>
                        </div>
                        <div class="card-body">
                            <div>
                                <div class="mb-4 row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            @error('slug')
                                                <div class="alert alert-dark">
                                                    <i class="bi bi-exclamation-triangle"></i>
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                            <label for="basicInput">Study Program</label>
                                            <input type="text" required class="form-control" id="basicInput"
                                                wire:model='name' placeholder="ex: Teknik Informatika">
                                        </div>
                                    </div>
                                </div>
                                <div class="gap-3 d-flex">
                                    @if ($isUpdate)
                                        <button wire:click='update' wire:loading.attr='disabled'
                                            class="btn btn-primary">
                                            Update
                                            <span wire:loading>
                                                <span class="spinner-border spinner-border-sm text-light"
                                                    role="status"></span>
                                            </span>
                                        </button>
                                    @else
                                        <button wire:click='create' wire:loading.attr='disabled'
                                            class="btn btn-primary">
                                            Add
                                            <span wire:loading>
                                                <span class="spinner-border spinner-border-sm text-light"
                                                    role="status"></span>
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
                                    <th>Study Program</th>
                                    <th>Created At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($study_programs as $study_program)
                                    <tr class="text-nowrap">
                                        <td class="text-bold-500">{{ $loop->index + $study_programs->firstItem() }}</td>
                                        <td class="text-bold-500">{{ $study_program->name }}</td>
                                        <td>{{ $study_program->created_at }}</td>
                                        <td class="gap-3 d-flex justify-content-center align-items-center">
                                            <button wire:click="edit('{{ $study_program->slug }}')"
                                                wire:key='{{ $study_program->slug }}' class="btn btn-warning">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>
                                            <button type="button"
                                                wire:click="deleteConfirm('{{ $study_program->slug }}')"
                                                class="block btn btn-danger" data-bs-toggle="modal"
                                                data-bs-target="#border-less">
                                                <i class="bi bi-trash3"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">Data Not Found</td>
                                    </tr>
                                @endforelse
                                <tr>
                            </tbody>
                        </table>
                    </div>
                    @if ($study_programs->isNotEmpty())
                        <div class="p-3 pt-4">
                            {{ $study_programs->links() }}
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
    </section>
</div>
