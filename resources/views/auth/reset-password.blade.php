<x-guest-layout>
    <div class="row justify-content-center">
        <div class="col-12 col-md-9 col-lg-7 col-xl-6 col-xxl-5">
            <div class="border-0 shadow-sm card rounded-4">
                <div class="p-3 card-body p-md-4 p-xl-5">
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-5">
                                <h3>Reset Password</h3>
                            </div>
                        </div>
                    </div>
                    <form action="{{ route('password.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="token" value="{{ $request->route('token') }}">
                        <div class="overflow-hidden row gy-3">
                            <div class="col-12">
                                <div class="mb-3 form-floating">
                                    <input type="email" class="form-control" name="email" id="email"
                                        placeholder="name@example.com" value="{{ old('email') }}" required>
                                    <label for="email" class="form-label">Email</label>
                                    @error('email')
                                        <span class="badge text-bg-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3 form-floating">
                                    <input type="password" class="form-control" name="password" id="password"
                                        placeholder="Password" required>
                                    <label for="password" class="form-label">Password</label>
                                    @error('password')
                                        <span class="badge text-bg-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3 form-floating">
                                    <input type="password" class="form-control" name="password_confirmation"
                                        id="password_confirmation" placeholder="min: 8 characters" required>
                                    <label for="password_confirmation" class="form-label">
                                        Password confirmation
                                    </label>
                                    @error('password_confirmation')
                                        <span class="badge text-bg-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="d-grid">
                                    <button class="btn btn-lg btn-primary" type="submit">
                                        Reset Password
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
