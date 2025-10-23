<div>
    <x-page-title :title="'Activity Lists'" :content="'All Activities data listed.'" />
    @if (session()->has('activity-success'))
        <div class="alert-success alert">
            {{ session('activity-success') }}
        </div>
    @endif
    @if (session()->has('activity-failed'))
        <div class="alert-danger alert">
            {{ session('activity-failed') }}
        </div>
    @endif
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
            </div>
        </div>
        <div class="card-content">
            <div class="table-responsive">
                <table class="table mb-0 table-lg">
                    <thead>
                        <tr class="text-center text-nowrap">
                            <th>No</th>
                            <th class="text-start">IP</th>
                            <th class="text-start">User Agent</th>
                            <th class="text-start">User</th>
                            <th class="text-start">Meta Data</th>
                            <th class="text-start">Category</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($activities as  $activity)
                            <tr class="text-center text-nowrap" wire:key='{{ $activity->id }}'>
                                <td class="text-bold-500">{{ $loop->index + $activities->firstItem() }}</td>
                                <td class="text-bold-500 text-start" title="{{ $activity->ip }}">
                                    {{ $activity->short_ip }}
                                </td>
                                <td class="text-bold-500 text-start" title="{{ $activity->user_agent }}">
                                    {{ $activity->short_user_agent }}
                                </td>
                                <td class="text-bold-500 text-start" title="{{ $activity->name }}">
                                    {{ $activity->short_name }}
                                </td>
                                <td class="text-bold-500 text-start" title="{{ $activity->meta_data }}">
                                    {{ $activity->short_meta_data }}
                                </td>
                                <td class="text-bold-500 text-start" title="{{ $activity->category }}">
                                    {{ $activity->short_category }}
                                </td>
                                <td class="gap-3 d-flex justify-content-center align-items-center">
                                    <button type="button" wire:click="deleteConfirm('{{ $activity->id }}')"
                                        class="block btn btn-danger" data-bs-toggle="modal"
                                        data-bs-target="#border-less">
                                        <i class="bi bi-trash3"></i>
                                    </button>
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
            @if ($activities->total() > $activities->perPage())
                <div class="p-3 pt-4">
                    {{ $activities->links() }}
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
