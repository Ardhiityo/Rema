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
                                    <label for="basicInput">Study Program</label>
                                    <input type="text" required class="form-control" id="basicInput" wire:model='name'
                                        placeholder="ex: Teknik Informatika">
                                    @error('slug')
                                        <span class="text-danger">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="gap-3 d-flex">
                            @if ($isUpdate)
                                <button wire:click='update' wire:loading.attr='disabled' class="btn btn-primary">
                                    Update
                                    <span wire:loading>
                                        <span class="spinner-border spinner-border-sm text-light" role="status"></span>
                                    </span>
                                </button>
                            @else
                                <button wire:click='create' wire:loading.attr='disabled' class="btn btn-primary">
                                    Add
                                    <span wire:loading>
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
                                    <form action="" method="post">
                                        <button class="btn btn-danger">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </form>
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
</section>
