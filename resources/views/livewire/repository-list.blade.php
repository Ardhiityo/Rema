<div>
    <div class="page-title">
        <div class="row">
            <div class="order-last col-12 col-md-6 order-md-1">
                <h3>Repository Lists</h3>
                <p class="text-subtitle text-muted">All Repositories data listed.</p>
            </div>
            <div class="order-first col-12 col-md-6 order-md-2">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('repository.index') }}">Repositories</a></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <div class="input-group">
                <label class="input-group-text" for="keyword">Keyword</label>
                <input type="text" wire:model.live.debounce.250ms='keyword' autofocus class="form-control"
                    id="keyword" placeholder="Search...">
                @if ($is_author_only || $is_admin)
                    <label class="input-group-text" for="status">Status</label>
                    <select name="status" id="status" class="form-select" wire:model.live='status_filter'>
                        <option value="approve">Approve</option>
                        <option value="pending">Pending</option>
                        <option value="revision">Revision</option>
                        <option value="reject">Reject</option>
                    </select>
                @endif
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
                            <th>Category</th>
                            @hasrole('admin')
                                <th>Status</th>
                                <th>Visibility</th>
                            @endhasrole
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($this->repositories as $repository)
                            <tr class="text-nowrap" wire:key='{{ $repository->slug }}'>
                                <td class="text-bold-500">{{ $loop->index + $this->repositories->firstItem() }}</td>
                                <td class="text-bold-500">{{ $repository->short_title }}</td>
                                <td class="text-bold-500">{{ $repository->author_name }}</td>
                                <td class="text-bold-500">{{ $repository->category_name }}</td>
                                @hasrole('admin')
                                    <td class="text-bold-500">
                                        <span class="{{ $repository->badge_status }}">
                                            {{ $repository->ucfirst_status }}
                                        </span>
                                    </td>
                                    <td class="text-bold-500">{{ $repository->ucfirst_visibility }}</td>
                                @endhasrole
                                <td class="gap-3 d-flex justify-content-center align-items-center">
                                    <a href="{{ route('repository.show', ['repository' => $repository->slug]) }}"
                                        class="btn btn-info">
                                        <i class="bi bi-eye-fill"></i>
                                    </a>
                                    @if ($is_admin || $is_author_only)
                                        <a href="{{ route('repository.edit', ['repository_slug' => $repository->slug]) }}"
                                            class="btn btn-warning">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                    @endif
                                    @hasrole('admin')
                                        <button type="button" wire:click="deleteConfirm('{{ $repository->slug }}')"
                                            class="block btn btn-danger" data-bs-toggle="modal"
                                            data-bs-target="#border-less">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    @endhasrole
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Data Not Found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($this->repositories->isNotEmpty())
                <div class="p-3 pt-4">
                    {{ $this->repositories->links() }}
                </div>
            @endif
        </div>
        <!--BorderLess Modal Modal -->
        <div wire:ignore.self class="text-left modal fade modal-borderless" id="border-less" tabindex="-1"
            role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirm deletion</h5>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete the data?</p>
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
    </div>
</div>
