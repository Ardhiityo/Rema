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
            <div class="gap-3 row d-flex gap-md-0">
                <div class="col-md-3">
                    <div class="input-group">
                        <label class="input-group-text" for="keyword">Title</label>
                        <input type="text" wire:model.live.debounce.250ms='title' autofocus class="form-control"
                            id="keyword">
                    </div>
                </div>
                @if ($is_author_only || $is_admin)
                    <div class="col-md-3">
                        <div class="input-group">
                            <label class="input-group-text" for="status">Status</label>
                            <select name="status" id="status" class="form-select" wire:model.live='status_filter'>
                                <option value="approve">Approve</option>
                                <option value="pending">Pending</option>
                                <option value="revision">Revision</option>
                                <option value="reject">Reject</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <label class="input-group-text" for="visibility">Visibility</label>
                            <select name="visibility" id="visibility" class="form-select" wire:model.live='visibility'>
                                <option value="public">Public</option>
                                <option value="protected">Protected</option>
                                <option value="private">Private</option>
                            </select>
                        </div>
                    </div>
                @endif
                <div class="col-md-3">
                    <div class="input-group">
                        <label class="input-group-text" for="year">Year</label>
                        <input type="number" wire:model.live.debounce.250ms='year' autofocus class="form-control"
                            id="year">
                    </div>
                </div>
                <div class="mt-3 col-12">
                    <button class="btn btn-primary w-100 btn-sm" wire:click='resetInput'>
                        <i class="bi bi-arrow-clockwise"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="card-content">
            <div class="table-responsive">
                <table class="table mb-0 table-lg">
                    <thead>
                        <tr class="text-nowrap">
                            <th class="text-center">No</th>
                            <th>Title</th>
                            @if (!$is_author_only)
                                <th>Author</th>
                            @endif
                            <th class="text-center">Visibility</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($this->meta_data as $data)
                            <tr class="text-nowrap" wire:key='{{ $data->slug }}'>
                                <td class="text-center text-bold-500">{{ $loop->index + $this->meta_data->firstItem() }}
                                </td>
                                <td class="text-bold-500">{{ $data->title }}</td>
                                @if (!$is_author_only)
                                    <td>
                                        @if ($data->author->user->avatar)
                                            <img src="{{ Storage::url($data->author->user->avatar) }}"
                                                alt="{{ $data->author->user->name }}"
                                                style="width: 38px; height: 38px; border-radius: 100%;">
                                        @else
                                            -
                                        @endif
                                        <span class="text-bold-500 ms-1">
                                            {{ $data->author->user->name }}
                                        </span>
                                    </td>
                                @endif
                                <td class="text-center text-bold-500">{{ $data->visibility }}</td>
                                <td class="gap-3 d-flex justify-content-center align-items-center">
                                    <a href="{{ route('repository.show', ['meta_data' => $data->slug]) }}"
                                        class="btn btn-info">
                                        <i class="bi bi-eye-fill"></i>
                                    </a>
                                    @if ($is_admin || $is_author_only)
                                        <a href="{{ route('repository.edit', ['meta_data_slug' => $data->slug]) }}"
                                            class="btn btn-warning">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                    @endif
                                    @hasrole('admin')
                                        <button type="button" wire:click="deleteConfirm('{{ $data->slug }}')"
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
            @if ($this->meta_data->isNotEmpty())
                <div class="p-3 pt-4">
                    {{ $this->meta_data->links() }}
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
