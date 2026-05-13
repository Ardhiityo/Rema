<div class="card">
    <div class="card-header">
        <h4 class="card-title">{{ $this->formTitle }}</h4>
    </div>
    <div class="card-body">
        @if (session()->has('staff-success'))
            <div class="alert-success alert">
                {{ session('staff-success') }}
            </div>
        @endif
        @if (session()->has('staff-failed'))
            <div class="alert-danger alert">
                {{ session('staff-failed') }}
            </div>
        @endif
        <div class="mb-4 row">
            <div class="col-md-6">
                <div class="gap-3 flex-column d-flex">
                    {{-- Name --}}
                    <div>
                        <label for="basicInput" class="form-label">
                            Name
                            <sup>
                                *
                            </sup>
                        </label>
                        <input type="text" required class="form-control" id="basicInput" wire:model='name'
                            placeholder="ex: Arya Adhi Prasetyo" name="name">
                        @error('name')
                            <span class="badge bg-danger">
                                <small>{{ $message }}</small>
                            </span>
                        @enderror
                    </div>
                    {{-- Name --}}

                    {{-- Email --}}
                    <div>
                        <label for="email" class="form-label">
                            Email
                            <sup>*</sup>
                        </label>
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
                        <label for="password" class="form-label">
                            Password
                                <sup>*</sup>
                        </label>
                        <input type="password" required class="form-control" id="password" wire:model='password'
                            placeholder="min 8 characters" name="password">
                        @error('password')
                            <span class="badge bg-danger">
                                <small>{{ $message }}</small>
                            </span>
                        @enderror
                        <p class="text-sm mt-2">
                            The password must be at least <b>8 characters</b> long and contain at least one <b>uppercase letter</b>, one <b>lowercase letter</b>, one <b>number</b>, and one <b>special character</b>.
                        </p>
                    </div>
                    {{-- Password --}}

                    {{-- Faculty --}}
                    <div>
                        <div class="input-group">
                            <label class="input-group-text" for="faculty_id" class="form-label">
                                Faculty <sup class="ms-1">*</sup>
                            </label>
                            <select class="form-select" id="faculty_id" wire:model='faculty_id'>
                                <option selected value="">Choose...</option>
                                @foreach ($faculties as $faculty)
                                    <option value="{{ $faculty->id }}">
                                        {{ $faculty->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('faculty_id')
                            <span class="badge bg-danger">
                                <small>{{ $message }}</small>
                            </span>
                        @enderror
                    </div>
                    {{-- Faculty --}}

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
                            <p class="text-sm mt-2">
                                The only allowed file extensions are <b>JPG, JPEG, and PNG</b>, the
                                maximum file size is <b>1 MB</b>.
                            </p>
                        </div>
                        @if ($display_avatar)
                            <div class="py-3">
                                <img src="{{ $display_avatar }}" class="rounded-circle" style="width: 100px; height: 100px;"
                                    alt="...">
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
                    <button wire:click='update' wire:loading.attr='disabled' wire:target='update' class="btn btn-primary">
                        <span wire:target='update' wire:loading.class='d-none'>Update</span>
                        <span wire:loading wire:target='update'>
                            <span class="spinner-border spinner-border-sm text-light" role="status"></span>
                        </span>
                    </button>
                @else
                    <button wire:click='create' wire:loading.attr='disabled' wire:target='create' class="btn btn-primary">
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