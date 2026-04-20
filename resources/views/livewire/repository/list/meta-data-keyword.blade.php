<div class="card">
    <div class="card-header">
        <h4 class="card-title">List of keywords</h4>
    </div>
    <div class="card-content">
        <div class="table-responsive">
            <table class="table mb-0 text-center table-lg">
                <thead>
                    <tr class="text-nowrap">
                        <th>No</th>
                        <th>Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($this->keywords as $keyword)
                        <tr class="text-nowrap" wire:key="{{ $keyword->slug }}">
                            <td class="text-bold-500">{{ $loop->iteration }}</td>
                            <td class="text-bold-500">{{ $keyword->name }}</td>
                            <td>
                                <div class="gap-3 d-flex justify-content-center align-items-center">
                                    <button class="btn btn-warning" 
                                        wire:click="$dispatch('edit-meta-data-keyword', {
                                            meta_data_id: @js($keyword->meta_data_id),
                                            keyword_slug: @js($keyword->slug)
                                        })">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <button type="button" class="block btn btn-danger" 
                                        wire:click="$dispatch('delete-confirm-meta-data-keyword', {
                                            meta_data_id: @js($keyword->meta_data_id),
                                            keyword_slug: @js($keyword->slug)
                                        })"
                                        data-bs-toggle="modal" data-bs-target="#modal-delete-keyword">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!--BorderLess Modal Modal -->
    <div wire:ignore.self class="text-left modal fade modal-borderless" id="modal-delete-keyword" tabindex="-1" role="dialog"
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
                    <button type="button" class="btn btn-danger ms-1"
                        wire:click="$dispatch('delete-meta-data-keyword')" data-bs-dismiss="modal">
                        <span>Accept</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>