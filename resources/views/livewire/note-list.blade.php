 <div class="card">
     <div class="card-content">
         @foreach ($this->notes as $note)
             <div class="card-body">
                 <div class="flex-column d-flex">
                     <h5 class="card-title">{{ $note->created_at }}</h5>
                 </div>
                 <div class="mt-3 row">
                     <div class="col-10">
                         <p class="card-text">
                             {{ $note->message }}
                         </p>
                     </div>
                     @hasrole('admin')
                         <div class="gap-3 col-2 d-flex justify-content-end">
                             <button class="btn btn-primary"
                                 wire:click="$dispatch('note-edit', { note_id: '{{ $note->id }}' })">
                                 <i class="bi bi-pencil-square"></i>
                             </button>
                             <button target="_blank" class="btn btn-danger"
                                 wire:click="$dispatch('note-delete-confirm', {note_id: '{{ $note->id }}'})"
                                 data-bs-toggle="modal" data-bs-target="#border-less">
                                 <i class="bi bi-trash3"></i>
                             </button>
                         </div>
                     @endhasrole
                 </div>
             </div>
         @endforeach
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
                     <button type="button" class="btn btn-danger ms-1" wire:click="$dispatch('note-delete')"
                         data-bs-dismiss="modal">
                         <i class="bx bx-check d-block d-sm-none"></i>
                         <span class="d-none d-sm-block">Accept</span>
                     </button>
                 </div>
             </div>
         </div>
     </div>
 </div>
