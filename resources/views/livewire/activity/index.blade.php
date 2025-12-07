<div>
    <x-page-title :title="'Activity Lists'" :content="'All Activities Data Listed.'" />
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
                <div class="col-md-4">
                    <div class="input-group">
                        <label class="input-group-text" for="keyword">Title</label>
                        <input type="text" wire:model.live.debounce.250ms='title' autofocus class="form-control"
                            id="keyword">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="input-group">
                        <label class="input-group-text" for="category">Category</label>
                        <select name="category" id="category" class="form-select" wire:model.live='category'>
                            @foreach ($categories as $category)
                                <option value="{{ $category->slug }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="input-group">
                        <label class="input-group-text" for="sort_by">Sort By</label>
                        <select name="sort_by" id="sort_by" class="form-select" wire:model.live='sort_by'>
                            <option value="popular">Popular</option>
                            <option value="unpopular">Unpopular</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary w-100" wire:click='resetInput' wire:target='resetInput'
                        wire:loading.attr='disabled'>
                        <span wire:target='resetInput' wire:loading.class='d-none'><i
                                class="bi bi-arrow-clockwise"></i></span>
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
                        <tr class="text-nowrap">
                            <th>No</th>
                            <th>Meta Data</th>
                            <th class="text-center">Category</th>
                            <th class="text-center">Views</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($activities as $key => $activity)
                            <tr class="text-nowrap" wire:key='{{ $key }}'>
                                <td class="text-bold-500">{{ $loop->index + $activities->firstItem() }}</td>
                                <td class="text-bold-500" title="{{ $activity->meta_data }}">
                                    {{ $activity->short_meta_data }}
                                </td>
                                <td class="text-center text-bold-500" title="{{ $activity->category }}">
                                    {{ $activity->category }}
                                </td>
                                <td class="text-center text-bold-500">
                                    {{ $activity->views }}
                                </td>
                                <td class="text-center text-bold-500">
                                    <a href="{{ route('activity.show', [
                                        'category_slug' => $activity->category_slug,
                                        'meta_data_slug' => $activity->meta_data_slug,
                                    ]) }}"
                                        class="btn btn-info"><i class="bi bi-eye-fill"></i></a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Data Not Found</td>
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
