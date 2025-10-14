<div class="card">
    <div class="card-header">
        <h4 class="card-title">List of repositories</h4>
    </div>
    <div class="card-content">
        <div class="table-responsive">
            <table class="table mb-0 text-center table-lg">
                <thead>
                    <tr class="text-nowrap">
                        <th>No</th>
                        <th>Category</th>
                        <th>File</th>
                        @if (!$this->islockForm)
                            <th>Action</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse ($this->repositories->categories as $key => $category)
                        <tr class="text-nowrap" wire:key='{{ $key }}'>
                            <td class="text-bold-500">{{ $loop->iteration }}</td>
                            <td class="text-bold-500">{{ $category->name }}</td>
                            <td>
                                <a href="{{ route('repository.read', [
                                    'category_slug' => $category->slug,
                                    'meta_data_slug' => $this->repositories->slug,
                                ]) }}"
                                    class="btn btn-info" target="_blank">
                                    <i class="bi bi-eye-fill"></i>
                                </a>
                            </td>
                            @if (!$this->islockForm)
                                <td>
                                    <div class="gap-3 d-flex justify-content-center align-items-center">
                                        <button class="btn btn-warning"
                                            wire:click="$dispatch('edit-repository-category', {
                                                meta_data_slug: @js($this->repositories->slug),
                                                category_slug: @js($category->slug)
                                                })">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <button type="button" class="block btn btn-danger"
                                            wire:click="$dispatch('delete-confirm-repository-category', {
                                                meta_data_slug: @js($this->repositories->slug),
                                                category_slug: @js($category->slug)
                                            })"
                                            data-bs-toggle="modal" data-bs-target="#border-less">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">Data Not Found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
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
                    <button type="button" class="btn btn-danger ms-1"
                        wire:click="$dispatch('delete-repository-category')" data-bs-dismiss="modal">
                        <i class="bx bx-check d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Accept</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
