<x-guest-layout>
    <div class="row justify-content-center">
        <div class="col-12 col-md-9 col-lg-7 col-xl-6 col-xxl-5">
            <div class="border-0 shadow-sm card rounded-4">
                <div class="p-3 card-body p-md-4 p-xl-5">
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-5">
                                <h3>Log in</h3>
                            </div>
                            @if (session()->has('status'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('status') }}
                                </div>
                            @endif
                        </div>
                    </div>
                    <form action="{{ route('login') }}" method="POST">
                        @csrf
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
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                    <label class="form-check-label text-secondary" for="remember">
                                        Keep me logged in
                                    </label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="d-grid">
                                    <button class="btn btn-lg btn-primary" type="submit">
                                        Log in now
                                    </button>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="gap-3 my-3 d-flex justify-content-center">
                                    <a href="{{ route('register') }}" class="link-secondary text-decoration-none">
                                        Create new account
                                    </a>
                                    <a href="{{ route('password.request') }}"
                                        class="link-secondary text-decoration-none">
                                        Forgot password
                                    </a>
                                </div>
                                <hr class="border-secondary-subtle">
                            </div>
                        </div>
                    </form>
                    <div class="row">
                        <div class="col-12">
                            <p class="text-center ">Or continue with</p>
                            <div class="gap-3 d-flex flex-column">
                                <a href="{{ route('google.redirect') }}"
                                    class="gap-1 btn btn-lg btn-secondary d-flex align-items-center justify-content-center">
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
</x-guest-layout>
