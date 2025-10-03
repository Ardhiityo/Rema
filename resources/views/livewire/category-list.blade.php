 <div class="card">
     <div class="card-header">
         <div class="gap-3 d-flex align-items-center">
             <input type="text" wire:model.live.debounce.250ms='keyword' autofocus class="form-control" id="basicInput"
                 placeholder="Search...">
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
                         <th>Category</th>
                         <th>Created At</th>
                         <th>Action</th>
                     </tr>
                 </thead>
                 <tbody>
                     @forelse ($this->categories as $category)
                         <tr class="text-nowrap" wire:key='{{ $category->slug }}'>
                             <td class="text-bold-500">{{ $loop->index + $this->categories->firstItem() }}</td>
                             <td class="text-bold-500">{{ $category->name }}</td>
                             <td>{{ $category->created_at }}</td>
                             <td class="gap-3 d-flex justify-content-center align-items-center">
                                 <button wire:click="$dispatch('category-edit', {category_id : '{{ $category->id }}'})"
                                     class="btn btn-warning">
                                     <i class="bi bi-pencil-square"></i>
                                 </button>
                                 <button type="button"
                                     wire:click="$dispatch('category-delete-confirm', {category_id: '{{ $category->id }}' })"
                                     class="block btn btn-danger" data-bs-toggle="modal" data-bs-target="#border-less">
                                     <i class="bi bi-trash3"></i>
                                 </button>
                             </td>
                         </tr>
                     @empty
                         <tr>
                             <td colspan="4" class="text-center">Data Not Found</td>
                         </tr>
                     @endforelse
                     <tr>
                 </tbody>
             </table>
         </div>
         @if ($this->categories->isNotEmpty())
             <div class="p-3 pt-4">
                 {{ $this->categories->links() }}
             </div>
         @endif
     </div>

     <!--BorderLess Modal Modal -->
     <div wire:ignore.self class="text-left modal fade modal-borderless" id="border-less" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel1" aria-hidden="true">
         <div class="modal-dialog modal-dialog-scrollable" role="document">
             <div class="modal-content">
                 <div class="modal-header">
                     <h5 class="modal-title">Border-Less</h5>
                 </div>
                 <div class="modal-body">
                     <p>Are you sure you want to delete the data?</p>
                 </div>
                 <div class="gap-2 modal-footer d-flex">
                     <button type="button" class="btn btn-primary" data-bs-dismiss="modal">
                         <i class="bx bx-x d-block d-sm-none"></i>
                         <span class="d-none d-sm-block">Close</span>
                     </button>
                     <button type="button" class="btn btn-danger ms-1" wire:click="$dispatch('category-delete')"
                         data-bs-dismiss="modal">
                         <i class="bx bx-check d-block d-sm-none"></i>
                         <span class="d-none d-sm-block">Accept</span>
                     </button>
                 </div>
             </div>
         </div>
     </div>
 </div>
