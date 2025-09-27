<div>
    <div class="mb-4 row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="basicInput">Study Program</label>
                <input type="text" required class="form-control" id="basicInput" wire:model='name'
                    placeholder="ex: Teknik Informatika">
                @error('slug')
                    <span class="text-danger">
                        {{ $message }}
                    </span>
                @enderror
            </div>
        </div>
    </div>
    <button wire:click='createStudyProgram' wire:loading.attr='disabled' class="btn btn-primary">
        Add
        <div wire:loading>
            <div class="spinner-border spinner-border-sm text-light" role="status"></div>
        </div>
    </button>
</div>
