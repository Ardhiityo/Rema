<div>
    <x-page-title :title="'Profile'" :content="'All data about your account.'" />
    <section id="multiple-column-form">
        <div class="row match-height">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Edit Profile</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            @if (session()->has('alert'))
                                <div class="alert alert-warning">
                                    <i class="bi bi-exclamation-triangle-fill"></i>
                                    {{ session('alert') }}
                                </div>
                            @elseif (session()->has('message'))
                                <div class="alert alert-success">
                                    {{ session('message') }}
                                </div>
                            @endif
                            <div class="form">
                                <div class="row">
                                    {{-- Name --}}
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="first-name-column" class="form-label">Name</label>
                                            <input type="text" id="first-name-column" class="form-control"
                                                placeholder="ex: Arya Adhi Prasetyo" name="name" wire:model='name'
                                                {{ $this->isLockForm ? 'disabled' : '' }}>
                                            @error('name')
                                                <span class="badge bg-danger text-wrap">
                                                    <small>{{ $message }}</small>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    {{-- Name --}}

                                    {{-- NIM --}}
                                    @hasrole('contributor')
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="first-name-column" class="form-label">NIM</label>
                                                <input type="text" id="first-name-column" class="form-control"
                                                    placeholder="ex: 22040004" name="nim" wire:model='nim'
                                                    {{ $this->isLockForm ? 'disabled' : '' }}>
                                                @error('nim')
                                                    <span class="badge bg-danger text-wrap">
                                                        <small>{{ $message }}</small>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    @endhasrole
                                    {{-- NIM --}}

                                    {{-- Study Program --}}
                                    @hasrole('contributor')
                                        <div class="my-2 col-12">
                                            <div class="input-group">
                                                <label class="input-group-text" for="inputGroupSelect01" class="form-label">
                                                    Study Program
                                                </label>
                                                <select class="form-select" id="inputGroupSelect01"
                                                    wire:model='study_program_id' {{ $this->isLockForm ? 'disabled' : '' }}>
                                                    <option selected>Choose...</option>
                                                    @foreach ($study_programs as $study_program)
                                                        <option value="{{ $study_program->id }}">
                                                            {{ $study_program->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @error('study_program_id')
                                                <span class="badge bg-danger text-wrap">
                                                    <small>{{ $message }}</small>
                                                </span>
                                            @enderror
                                        </div>
                                    @endhasrole
                                    {{-- Study Program --}}

                                    {{-- Email --}}
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="email" id="email" class="form-control" wire:model='email'
                                                name="email" disabled>
                                        </div>
                                    </div>
                                    {{-- Email --}}

                                    {{-- Password --}}
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="city-column" class="form-label">Password</label>
                                            <input type="password" id="city-column" class="form-control"
                                                placeholder="min: 8 characters" name="city-column"
                                                wire:model='password'>
                                            @error('password')
                                                <span class="badge bg-danger text-wrap">
                                                    <small>{{ $message }}</small>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    {{-- Password --}}

                                    {{-- Avatar --}}
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="country-floating" class="form-label">Avatar</label>
                                            <input class="form-control" type="file" id="formFile"
                                                accept=".jpg,.jpeg,.png" wire:model='avatar'
                                                {{ $this->isLockForm ? 'disabled' : '' }}>
                                            @error('avatar')
                                                <span class="badge bg-danger text-wrap">
                                                    <small>{{ $message }}</small>
                                                </span>
                                            @enderror
                                        </div>
                                        @if ($display_avatar)
                                            <div class="py-3">
                                                <img src="{{ Storage::url($display_avatar) }}" class="rounded-circle"
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

                                    <div class="gap-3 mt-4 col-12 d-flex">
                                        <button type="submit" wire:click='update' class="mb-1 btn btn-primary"
                                            wire:loading.attr='disabled' wire:target='update'>
                                            Update
                                            <span wire:loading wire:target='update'>
                                                <span class="spinner-border spinner-border-sm text-light"
                                                    role="status"></span>
                                            </span>
                                        </button>
                                        <button type="reset" wire:click='resetInput'
                                            class="mb-1 btn btn-warning">Clear</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
