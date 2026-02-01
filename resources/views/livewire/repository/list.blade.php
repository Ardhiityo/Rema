<div>
    <x-page-title :title="'Repository Lists'" :content="'All Repositories Data Listed.'" />
    @if (session()->has('repository-list-success'))
        <div class="alert-success alert">
            {{ session('repository-list-success') }}
        </div>
    @endif
    @if (session()->has('repository-list-failed'))
        <div class="alert-danger alert">
            {{ session('repository-list-failed') }}
        </div>
    @endif
    <div class="card">
        <div class="card-header">
            <div class="gap-3 row d-flex gap-md-0">
                <div class="col-md-4">
                    <div class="input-group">
                        <label class="input-group-text" for="keyword">
                            Title @if ($is_master_data)
                                /Author
                            @endif
                        </label>
                        <input type="text" wire:model.live.debounce.250ms='keyword' autofocus class="form-control"
                            id="keyword" placeholder="Search...">
                    </div>
                </div>
                @if ($is_author || $is_admin)
                    <div class="col-md-3">
                        <div class="input-group">
                            <label class="input-group-text" for="status">Status</label>
                            <select name="status" id="status" class="form-select" wire:model.live='status_filter'>
                                <option value="approve">Approve</option>
                                <option value="process">Process</option>
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
                                <option value="private">Private</option>
                            </select>
                        </div>
                    </div>
                @endif
                <div class="col-md-2">
                    <div class="input-group">
                        <label class="input-group-text" for="year">Year</label>
                        <input type="number" wire:model.live.debounce.250ms='year' autofocus class="form-control"
                            id="year" placeholder="Search...">
                    </div>
                </div>
                <div class="mt-3 col-12">
                    <button class="btn btn-primary w-100 btn-sm" wire:click='resetInput' wire:target='resetInput'
                        wire:loading.attr='disabled'>
                        <span wire:target='resetInput' wire:loading.class='d-none'>Reset</span>
                        <span wire:loading wire:target='resetInput'>
                            <span class="spinner-border spinner-border-sm text-light" role="status"></span>
                        </span>
                    </button>
                </div>
            </div>
        </div>
        <div class="card-content">
            <div class="table-responsive">
                <table class="table mb-0 table-lg">
                    <thead>
                        <tr class="text-center text-nowrap">
                            <th class="text-start">No</th>
                            <th class="text-start">Title</th>
                            @if (! $is_author)
                                <th>Author</th>
                            @endif
                            @if (! $is_author)
                                <th>NIM</th>
                            @endif
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($meta_data as $data)
                            <tr class="text-center text-nowrap" wire:key='{{ $data->slug }}'>
                                <td class="text-bold-500 text-start">{{ $loop->index + $meta_data->firstItem() }}</td>
                                <td class="text-bold-500 text-start" title="{{ $data->title }}">
                                    {{ $data->short_title }}
                                </td>
                                @if (! $is_author)
                                    <td>
                                        <span class="text-bold-500 ms-1" title="{{ $data->name }}">
                                            {{ $data->short_name }}
                                        </span>
                                    </td>
                                @endif
                                @if (! $is_author)
                                    <td class="text-bold-500" title="{{ $data->nim }}">{{ $data->nim }}</td>
                                @endif
                                <td class="gap-3 d-flex justify-content-center align-items-center">
                                    <a href="{{ route('repository.show', ['meta_data' => $data->slug]) }}"
                                        class="btn btn-info" title="view">
                                        <i class="bi bi-eye-fill"></i>
                                    </a>
                                    @can('update', $data->toModel())
                                        <a href="{{ route('repository.edit', ['meta_data_slug' => $data->slug]) }}"
                                            class="btn btn-warning" title="edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                    @endcan
                                    @can('delete', $data->toModel())
                                        <button type="button" class="block btn btn-danger" data-bs-toggle="modal"
                                            data-bs-target="#border-less" wire:click="deleteConfirm('{{ $data->slug }}')"
                                            title="delete">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    @endcan
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
            @if ($meta_data->total() > $meta_data->perPage())
                <div class="p-3 pt-4">
                    {{ $meta_data->links() }}
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
                <div class="gap-2 modal-footer d-flex align-items-center">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">
                        <span>Close</span>
                    </button>
                    <button type="button" class="btn btn-danger ms-1" wire:click='delete' data-bs-dismiss="modal">
                        <span>Accept</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>