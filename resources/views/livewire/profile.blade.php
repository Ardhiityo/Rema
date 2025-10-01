<div>
    <div class="page-title">
        <div class="row">
            <div class="order-last col-12 col-md-6 order-md-1">
                <h3>My Accounts</h3>
                <p class="text-subtitle text-muted">All data about your account.</p>
            </div>
            <div class="order-first col-12 col-md-6 order-md-2">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html">My Accounts</a></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <section id="multiple-column-form">
        <div class="row match-height">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Edit Profile</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <div class="form">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            @error('name')
                                                <div class="alert alert-dark">
                                                    <i class="bi bi-exclamation-triangle"></i>
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                            <label for="first-name-column" class="form-label">Name</label>
                                            <input type="text" id="first-name-column" class="form-control"
                                                placeholder="ex: Arya Adhi Prasetyo" name="name" wire:model='name'>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            @error('email')
                                                <div class="alert alert-dark">
                                                    <i class="bi bi-exclamation-triangle"></i>
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                            <label for="last-name-column" class="form-label">Email</label>
                                            <input type="email" id="last-name-column" class="form-control"
                                                placeholder="ex: ardhiityo229@gmail.com" name="email"
                                                wire:model='email'>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            @error('password')
                                                <div class="alert alert-dark">
                                                    <i class="bi bi-exclamation-triangle"></i>
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                            <label for="city-column" class="form-label">Password</label>
                                            <input type="password" id="city-column" class="form-control"
                                                placeholder="min: 8 characters" name="city-column"
                                                wire:model='password'>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            @error('avatar')
                                                <div class="alert alert-dark">
                                                    <i class="bi bi-exclamation-triangle"></i>
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                            <label for="country-floating" class="form-label">Avatar</label>
                                            <input class="form-control" type="file" id="formFile"
                                                accept="application/jpg,application/png" wire:model='avatar'>
                                        </div>
                                    </div>
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
