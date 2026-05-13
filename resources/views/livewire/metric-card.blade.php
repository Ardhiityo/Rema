<div class="flex-wrap row d-flex align-items-center">
    <div class="col-6 col-lg-4 col-md-6">
        <div class="card">
            <div class="px-4 py-3 card-body">
                <div class="row" style="height: 120px">
                    <div
                        class="col-md-4 d-flex justify-content-xxl-center align-items-center col-lg-12 col-xl-12 col-xxl-4 justify-content-lg-start">
                        <div class="mb-2 stats-icon purple">
                            <i class="iconly-boldProfile"></i>
                        </div>
                    </div>
                    <div
                        class="mt-2 text-md-start mt-xxl-0 col-md-8 col-lg-12 col-xl-12 col-xxl-8 d-flex flex-column justify-content-center">
                        <h6 class="font-semibold text-muted">Authors</h6>
                        <h6 class="mb-0 font-extrabold">{{ $this->authors_count }}</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-4 col-md-6">
        <div class="card">
            <div class="px-4 py-3 card-body">
                <div class="row" style="height: 120px">
                    <div
                        class="col-md-4 d-flex justify-content-xxl-center align-items-center col-lg-12 col-xl-12 col-xxl-4 justify-content-lg-start">
                        <div class="mb-2 stats-icon purple">
                            <i class="iconly-boldBookmark"></i>
                        </div>
                    </div>
                    <div
                        class="mt-2 text-md-start mt-xxl-0 col-md-8 col-lg-12 col-xl-12 col-xxl-8 d-flex flex-column justify-content-center">
                        <h6 class="font-semibold text-muted">Repositories</h6>
                        <h6 class="mb-0 font-extrabold">{{ $this->repositories_count }}</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
       <div class="col-6 col-lg-4 col-md-6">
        <div class="card">
            <div class="px-4 py-3 card-body">
                <div class="row" style="height: 120px">
                    <div
                        class="col-md-4 d-flex justify-content-xxl-center align-items-center col-lg-12 col-xl-12 col-xxl-4 justify-content-lg-start">
                        <div class="mb-2 stats-icon purple">
                            <i class="iconly-boldCategory"></i>
                        </div>
                    </div>
                    <div
                        class="mt-2 text-md-start mt-xxl-0 col-md-8 col-lg-12 col-xl-12 col-xxl-8 d-flex flex-column justify-content-center">
                        <h6 class="font-semibold text-muted">Categories</h6>
                        <h6 class="mb-0 font-extrabold">{{ $this->categories_count }}</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
