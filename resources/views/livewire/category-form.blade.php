   <section class="section">
       <div class="card">
           <div class="card-header">
               <h4 class="card-title">{{ $this->formTitle }}</h4>
           </div>
           <div class="card-body">
               @if (session()->has('message'))
                   <div class="alert-success alert">
                       {{ session('message') }}
                   </div>
               @endif
               <div class="mb-4 row">
                   <div class="col-md-6">
                       <div class="form-group">
                           <label for="category" class="form-label">Category</label>
                           <input type="text" required class="form-control" id="category" wire:model='name'
                               placeholder="ex: Skripsi">
                           @error('slug')
                               <span class="badge bg-danger">
                                   {{ $message }}
                               </span>
                           @enderror
                       </div>
                   </div>
               </div>
               <div class="gap-3 d-flex">
                   @if ($is_update)
                       <button wire:click='update' wire:loading.attr='disabled' wire:target='update'
                           class="btn btn-primary">
                           Update
                           <span wire:loading wire:target='update'>
                               <span class="spinner-border spinner-border-sm text-light" role="status"></span>
                           </span>
                       </button>
                   @else
                       <button wire:click='create' wire:loading.attr='disabled' wire:target='create'
                           class="btn btn-primary">
                           Add
                           <span wire:loading wire:target='create'>
                               <span class="spinner-border spinner-border-sm text-light" role="status"></span>
                           </span>
                       </button>
                   @endif
                   <button wire:click='resetInput' class="btn btn-warning">
                       Clear
                   </button>
               </div>
           </div>
       </div>
   </section>
