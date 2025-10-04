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
                        </div>
                    </div>
                    <form action="{{ route('password.email') }}" method="POST">
                        @csrf
                        <div class="overflow-hidden row gy-3">
                            <div class="col-12">
                                @if (session()->has('status'))
                                    <div class="alert alert-success" role="alert">
                                        {{ session('status') }}
                                    </div>
                                @endif
                                <div class="mb-3 form-floating">
                                    <input type="email" class="form-control" name="email" id="email"
                                        placeholder="name@example.com" required>
                                    <label for="email" class="form-label">Email</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="d-grid">
                                    <button class="btn btn-lg btn-primary" type="submit">
                                        Send password reset link
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
