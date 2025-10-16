<div class="row">
    <div class="col-6 col-lg-4 col-md-6">
        <div class="card">
            <div class="px-4 card-body py-4-5">
                <div class="row">
                    <div
                        class="col-md-4 d-flex justify-content-md-start justify-content-center align-items-center col-lg-12 col-xl-12 col-xxl-5 justify-content-start ">
                        <div class="mb-2 stats-icon purple">
                            <i class="iconly-boldProfile"></i>
                        </div>
                    </div>
                    <div class="text-center text-md-start col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                        <h6 class="font-semibold text-muted">Authors</h6>
                        <h6 class="mb-0 font-extrabold">{{ $this->authors_count }}</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @foreach ($this->metrics as $metric)
        <div class="col-6 col-lg-4 col-md-6">
            <div class="card">
                <div class="px-4 card-body py-4-5">
                    <div class="text-center row">
                        <div
                            class="col-md-4 d-flex justify-content-md-start justify-content-center align-items-center col-lg-12 col-xl-12 col-xxl-5 justify-content-start ">
                            <div class="mb-2 stats-icon blue">
                                <i class="iconly-boldBookmark"></i>
                            </div>
                        </div>
                        <div class="text-center text-md-start col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                            <h6 class="font-semibold text-muted">{{ $metric->study_program }}</h6>
                            <h6 class="mb-0 font-extrabold">{{ $metric->total }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
