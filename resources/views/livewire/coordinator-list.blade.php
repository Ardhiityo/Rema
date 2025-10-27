 <div class="card">
     <div class="card-header">
         <div class="gap-3 d-flex align-items-center">
             <div class="input-group">
                 <label class="input-group-text" for="keyword">NIDN / Coordinator</label>
                 <input name="keyword" type="text" wire:model.live.debounce.250ms='keyword' autofocus
                     class="form-control" id="status" placeholder="Search...">
             </div>
             <div class="col-md-1">
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
                         <th>NIDN</th>
                         <th>Coordinator</th>
                         <th>Position</th>
                         <th>Action</th>
                     </tr>
                 </thead>
                 <tbody>
                     @forelse ($this->coordinators as $coordinator)
                         <tr class="text-nowrap" wire:key='{{ $coordinator->id }}'>
                             <td class="text-bold-500">{{ $loop->index + $this->coordinators->firstItem() }}</td>
                             <td class="text-bold-500">{{ $coordinator->nidn }}</td>
                             <td class="text-bold-500">{{ $coordinator->name }}</td>
                             <td class="text-bold-500">{{ $coordinator->position }}</td>
                             <td>
                                 <div class="gap-3 d-flex justify-content-center align-items-center">
                                     <button
                                         wire:click="$dispatch('coordinator-edit', {coordinator_id: '{{ $coordinator->id }}'})"
                                         class="btn btn-warning">
                                         <i class="bi bi-pencil-square"></i>
                                     </button>
                                     <button type="button"
                                         wire:click="$dispatch('coordinator-delete-confirm', {coordinator_id : '{{ $coordinator->id }}'})"
                                         class="block btn btn-danger" data-bs-toggle="modal"
                                         data-bs-target="#border-less">
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
                     <tr>
                 </tbody>
             </table>
         </div>
         @if ($this->coordinators->total() > $this->coordinators->perPage())
             <div class="p-3 pt-4">
                 {{ $this->coordinators->links() }}
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
                     <button type="button" class="btn btn-danger ms-1" wire:click="$dispatch('coordinator-delete')"
                         data-bs-dismiss="modal">
                         <i class="bx bx-check d-block d-sm-none"></i>
                         <span class="d-none d-sm-block">Accept</span>
                     </button>
                 </div>
             </div>
         </div>
     </div>
 </div>
