<div class="card">
    <div class="card-header">
        <div class="input-group">
            <label class="input-group-text" for="status">Keyword</label>
            <input type="text" wire:model.live.debounce.250ms='keyword' autofocus class="form-control" id="status"
                placeholder="Search...">
            <label class="input-group-text" for="status">Status</label>
            <select name="status" id="status" class="form-select" wire:model.live='status_filter'>
                <option value="pending">Pending</option>
                <option value="approve">Approve</option>
                <option value="reject">Reject</option>
            </select>
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
                        <th>Avatar</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($this->authors as $author)
                        <tr class="text-nowrap">
                            <td class="text-bold-500">{{ $loop->index + $this->authors->firstItem() }}</td>
                            <td class="text-bold-500">{{ $author->nim }}</td>
                            <td class="text-bold-500">{{ $author->name }}</td>
                            <td class="text-bold-500">{{ $author->study_program_name }}</td>
                            <td class="text-bold-500">
                                <img src="{{ $author->avatar }}" alt="{{ $author->name }}" class="rounded-pill"
                                    style="max-width: 38px">
                            </td>
                            <td class="gap-3 d-flex justify-content-center align-items-center">
                                <button wire:click="$dispatch('author-edit', { author_id: '{{ $author->author_id }}' })"
                                    wire:key="{{ $author->nim }}" class="btn btn-warning">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <button type="button"
                                    wire:click="$dispatch('author-delete-confirm', {author_id : '{{ $author->author_id }}'})"
                                    class="block btn btn-danger" data-bs-toggle="modal" data-bs-target="#border-less">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Data Not Found</td>
                        </tr>
                    @endforelse
                    <tr>
                </tbody>
            </table>
        </div>
        @if ($this->authors->isNotEmpty())
            <div class="p-3 pt-4">
                {{ $this->authors->links() }}
            </div>
        @endif
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
                    <button type="button" class="btn btn-danger ms-1" wire:click="$dispatch('author-delete')"
                        data-bs-dismiss="modal">
                        <i class="bx bx-check d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Accept</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
