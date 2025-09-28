<section>
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
                            <th>Title</th>
                            <th>Author</th>
                            <th>Type</th>
                            <th>Published At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($repositories as $repository)
                            <tr class="text-nowrap" wire:key='{{ $repository->slug }}'>
                                <td class="text-bold-500">{{ $loop->index + $repositories->firstItem() }}</td>
                                <td class="text-bold-500">{{ $repository->title }}</td>
                                <td class="text-bold-500">{{ $repository->author_name }}</td>
                                <td class="text-bold-500">{{ $repository->type }}</td>
                                <td class="text-bold-500">{{ $repository->publised_at_to_dfy }}</td>
                                <td class="gap-3 d-flex justify-content-center align-items-center">
                                    <a href="{{ route('repository.show', ['repository' => $repository->slug]) }}"
                                        class="btn btn-info">
                                        <i class="bi bi-eye-fill"></i>
                                    </a>
                                    <a href="{{ route('repository.edit', ['repository_slug' => $repository->slug]) }}"
                                        class="btn btn-warning">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <button type="button" wire:click="deleteConfirm('{{ $repository->slug }}')"
                                        class="block btn btn-danger" data-bs-toggle="modal"
                                        data-bs-target="#border-less">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Data Not Found</td>
                            </tr>
                        @endforelse
                        <tr>
                    </tbody>
                </table>
            </div>
            @if ($repositories->isNotEmpty())
                <div class="p-3 pt-4">
                    {{ $repositories->links() }}
                </div>
            @endif
        </div>
    </div>

    <!--BorderLess Modal Modal -->
    <div wire:ignore.self class="text-left modal fade modal-borderless" id="border-less" tabindex="-1" role="dialog"
        aria-labelledby="myModalLabel1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Border-Less</h5>
                    <button type="button" class="close rounded-pill" data-bs-dismiss="modal" aria-label="Close">
                        <i data-feather="x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete the {{ $title }} data?</p>
                </div>
                <div class="gap-2 modal-footer d-flex">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Close</span>
                    </button>
                    <button type="button" class="btn btn-danger ms-1" wire:click='delete' data-bs-dismiss="modal">
                        <i class="bx bx-check d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Accept</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>
