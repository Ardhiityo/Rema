<x-guest-layout>
    <!-- Login 6 - Bootstrap Brain Component -->
    <section class="p-3 bg-primary p-md-4 p-xl-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-md-9 col-lg-7 col-xl-6 col-xxl-5">
                    <div class="border-0 shadow-sm card rounded-4">
                        <div class="p-3 card-body p-md-4 p-xl-5">
                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-5">
                                        <h3>Register</h3>
                                    </div>
                                </div>
                            </div>
                            <form action="{{ route('register') }}" method="POST">
                                @csrf
                                <div class="overflow-hidden row gy-3">
                                    <div class="col-12">
                                        <div class="mb-3 form-floating">
                                            <input type="text" class="form-control" name="name" id="name"
                                                placeholder="ex: Arya Adhi Prasetyo" required
                                                value="{{ old('name') }}">
                                            <label for="name" class="form-label">Name</label>
                                            @error('name')
                                                <span class="badge text-bg-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-3 form-floating">
                                            <input type="email" class="form-control" name="email" id="email"
                                                placeholder="name@example.com" required value="{{ old('email') }}">
                                            <label for="email" class="form-label">Email</label>
                                            @error('email')
                                                <span class="badge text-bg-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-3 form-floating">
                                            <input type="password" class="form-control" name="password" id="password"
                                                placeholder="min: 8 characters" required>
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
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="remember"
                                                id="remember">
                                            <label class="form-check-label text-secondary" for="remember">
                                                Keep me logged in
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="d-grid">
                                            <button class="btn btn-lg btn-primary" type="submit">
                                                Register now
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <div class="row">
                                <div class="col-12">
                                    <hr class="mt-5 mb-4 border-secondary-subtle">
                                    <div class="d-flex justify-content-end">
                                        <a href="{{ route('login') }}" class="link-secondary text-decoration-none">
                                            Login
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <p class="mt-5 mb-4">Or continue with</p>
                                    <div class="gap-3 d-flex flex-column">
                                        <a href="#!"
                                            class="gap-1 btn btn-lg btn-danger d-flex align-items-center justify-content-center">
                                            <i class="bi bi-google"></i>
                                            <span class="ms-2 fs-6 text-uppercase">Sign in With Google</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-guest-layout>
