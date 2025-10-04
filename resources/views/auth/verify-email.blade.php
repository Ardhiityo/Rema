<x-guest-layout>
    <div class="row justify-content-center">
        <div class="col-12 col-md-9 col-lg-7 col-xl-6 col-xxl-5">
            <div class="border-0 shadow-sm card rounded-4">
                <div class="p-3 card-body p-md-4 p-xl-5">
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-5">
                                <h3>Verify Email</h3>
                            </div>
                            @if (session('status') === 'verification-link-sent')
                                <div class="alert alert-success" role="alert">
                                    A new verification link has been sent to the email address you provided during
                                    registration.
                                </div>
                            @endif
                        </div>
                    </div>
                    <form action="{{ route('verification.send') }}" method="POST">
                        @csrf
                        <div class="overflow-hidden row gy-3">
                            <div class="col-12">
                                <p>
                                    Thanks for signing up! Before getting started, could you verify your email address
                                    by
                                    clicking on the link we just emailed to you? If you didn't receive the email, we
                                    will gladly send you another.
                                </p>
                            </div>
                            <div class="col-12">
                                <div class="d-grid">
                                    <button class="btn btn-lg btn-primary" type="submit">
                                        Resend Verification Email
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
