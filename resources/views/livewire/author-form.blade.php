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
                <div class="gap-3 flex-column d-flex">
                    {{-- NIM --}}
                    <div>
                        <label for="basicInput" class="form-label">NIM</label>
                        <input type="text" required class="form-control" id="basicInput" wire:model='nim'
                            placeholder="ex: 22040004" name="nim">
                        @error('nim')
                            <span class="badge bg-danger">
                                <small>{{ $message }}</small>
                            </span>
                        @enderror
                    </div>
                    {{-- NIM --}}

                    {{-- Email --}}
                    <div>
                        <label for="email" class="form-label">Email</label>
                        <input type="email" required class="form-control" id="email" wire:model='email'
                            placeholder="ex: ardhiityo229@gmail.com" name="email">
                        @error('email')
                            <span class="badge bg-danger">
                                <small>{{ $message }}</small>
                            </span>
                        @enderror
                    </div>
                    {{-- Email --}}

                    {{-- Password --}}
                    <div>
                        <label for="password" class="form-label">Password</label>
                        <input type="password" required class="form-control" id="password" wire:model='password'
                            placeholder="min 8 characters" name="password">
                        @error('password')
                            <span class="badge bg-danger">
                                <small>{{ $message }}</small>
                            </span>
                        @enderror
                    </div>
                    {{-- Password --}}
                </div>
            </div>
            <div class="col-md-6">
                <div class="gap-3 flex-column d-flex">
                    {{-- Name --}}
                    <div>
                        <label for="basicInput" class="form-label">Name</label>
                        <input type="text" required class="form-control" id="basicInput" wire:model='name'
                            placeholder="ex: Arya Adhi Prasetyo" name="name">
                        @error('name')
                            <span class="badge bg-danger">
                                <small>{{ $message }}</small>
                            </span>
                        @enderror
                    </div>
                    {{-- Name --}}

                    {{-- Study Program --}}
                    <div>
                        <div class="input-group">
                            <label class="input-group-text" for="inputGroupSelect01">
                                Study Program
                            </label>
                            <select class="form-select" id="inputGroupSelect01" wire:model='study_program_id'>
                                <option selected>Choose...</option>
                                @foreach ($study_programs as $study_program)
                                    <option value="{{ $study_program->id }}">
                                        {{ $study_program->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('study_program_id')
                            <span class="badge bg-danger">
                                <small>{{ $message }}</small>
                            </span>
                        @enderror
                    </div>
                    {{-- Study Program --}}

                    {{-- Status --}}
                    <div>
                        <div class="input-group">
                            <label class="input-group-text" for="status" class="form-label">
                                Status
                            </label>
                            <select class="form-select" id="status" wire:model='status'>
                                <option value="">
                                    Choose...
                                </option>
                                <option value="approve">
                                    Approve
                                </option>
                                <option value="pending">
                                    Pending
                                </option>
                                <option value="reject">
                                    Reject
                                </option>
                            </select>
                        </div>
                        @error('status')
                            <span class="badge bg-danger">
                                <small>{{ $message }}</small>
                            </span>
                        @enderror
                    </div>
                    {{-- Status --}}

                    {{-- Avatar --}}
                    <div>
                        <div>
                            <label for="avatar" class="form-label">
                                Avatar
                            </label>
                            <input class="form-control" wire:model='avatar' type="file" id="avatar"
                                accept=".jpg,.jpeg,.png">
                            @error('avatar')
                                <span class="badge bg-danger">
                                    <small> {{ $message }}</small>
                                </span>
                            @enderror
                        </div>
                        @if ($display_avatar)
                            <div class="py-3">
                                <img src="{{ $display_avatar }}" class="rounded-circle"
                                    style="width: 100px; height: 100px;" alt="...">
                            </div>
                        @endif
                        @if ($avatar)
                            <div class="py-3">
                                <img src="{{ $avatar->temporaryUrl() }}" class="rounded-circle"
                                    style="width: 100px; height: 100px;" alt="...">
                            </div>
                        @endif
                    </div>
                    {{-- Avatar --}}
                </div>
            </div>
            <div class="gap-3 mt-4 d-flex">
                @if ($is_update)
                    <button wire:click='update' wire:loading.attr='disabled' wire:target='update'
                        class="btn btn-primary">
                        <span wire:target='update' wire:loading.class='d-none'>Update</span>
                        <span wire:loading wire:target='update'>
                            <span class="spinner-border spinner-border-sm text-light" role="status"></span>
                        </span>
                    </button>
                @else
                    <button wire:click='create' wire:loading.attr='disabled' wire:target='create'
                        class="btn btn-primary">
                        <span wire:target='create' wire:loading.class='d-none'>Add</span>
                        <span wire:loading wire:target='create'>
                            <span class="spinner-border spinner-border-sm text-light" role="status"></span>
                        </span>
                    </button>
                @endif
                <button wire:click='resetInput' class="btn btn-warning" wire:loading.attr='disabled'
                    wire:target='resetInput'>
                    <span wire:target='resetInput' wire:loading.class='d-none'>Clear</span>
                    <span wire:loading wire:target='resetInput'>
                        <span class="spinner-border spinner-border-sm text-primary" role="status"></span>
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>
