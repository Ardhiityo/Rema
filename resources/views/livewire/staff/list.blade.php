<div class="card">
    <div class="card-header">
        <div class="gap-3 row d-flex gap-md-0">
            <div class="col-md-5">
                <div class="input-group">
                    <label class="input-group-text" for="keyword">Name</label>
                    <input name="keyword" type="text" wire:model.live.debounce.250ms='keyword' autofocus
                        class="form-control" id="status" placeholder="Search...">
                </div>
            </div>
            <div class="col-md-5">
                <div class="input-group">
                    <label class="input-group-text" for="faculty_slug">Faculty</label>
                    <select name="faculty_slug" id="faculty_slug" class="form-select"
                        wire:model.live='faculty_slug'>
                        <option value="">All</option>
                        @foreach ($faculties as $faculty)
                            <option value="{{ $faculty->slug }}">
                                {{ $faculty->name }}
                            </option>
                        @endforeach
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
            <table class="table mb-0 text-center table-lg">
                <thead>
                    <tr class="text-nowrap">
                        <th>No</th>
                        <th>Name</th>
                        <th>Faculty</th>
                        <th>Avatar</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($this->staffs as $key => $staff)
                        <tr class="text-nowrap" wire:key="{{ $key }}">
                            <td class="text-bold-500">{{ $loop->index + $this->staffs->firstItem() }}</td>
                            <td class="text-bold-500" title="{{ $staff->name }}">{{ $staff->short_name }}</td>
                            <td class="text-bold-500" title="{{ $staff->faculty }}">{{ $staff->faculty }}</td>
                            <td>
                                <div class="d-flex justify-content-center">
                                    <img src="{{ $staff->avatar }}" alt="{{ $staff->name }}"
                                        style="width: 38px; height: 38px; border-radius: 100%;">
                                </div>
                            </td>
                            <td>
                                <div class="gap-3 d-flex justify-content-center align-items-center">
                                    <button wire:click="$dispatch('staff-edit', { staff_id: '{{ $staff->id }}' })"
                                        class="btn btn-warning">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <button type="button"
                                        wire:click="$dispatch('staff-delete-confirm', {staff_id: '{{ $staff->id }}'})"
                                        class="block btn btn-danger" data-bs-toggle="modal"
                                        data-bs-target="#staff-delete-confirm">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </div>
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
        @if ($this->staffs->total() > $this->staffs->perPage())
            <div class="p-3 pt-4">
                {{ $this->staffs->links() }}
            </div>
        @endif
    </div>

    <!--BorderLess Modal Modal -->
    <div wire:ignore.self class="text-left modal fade modal-borderless" id="staff-delete-confirm" tabindex="-1" role="dialog"
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
                    <button type="button" class="btn btn-danger ms-1" wire:click="$dispatch('staff-delete')"
                        data-bs-dismiss="modal">
                        <span>Accept</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>