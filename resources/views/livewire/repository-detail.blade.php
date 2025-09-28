<div>
    <div class="page-title">
        <div class="row">
            <div class="order-last col-12 col-md-6 order-md-1">
                <h3>Repositories</h3>
                <p class="text-subtitle text-muted">Give textual form controls like input upgrade with
                    custom styles,
                    sizing, focus states, and more.</p>
            </div>
            <div class="order-first col-12 col-md-6 order-md-2">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html">Repositories</a></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <section class="section">
        <div>
            <section class="section">
                <div class="card">
                    <div class="pb-0 card-header">
                        <h4 class="card-title">{{ $title }}</h4>
                        <h6>
                            <small>{{ $author }} | {{ $nim }} | {{ $study_program }}</small>
                        </h6>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <p>
                                {{ $abstract }}
                            </p>
                            <ul class="list-group">
                                <li class="list-group-item">{{ $type }}</li>
                                <li class="list-group-item">{{ $published_at }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </section>
</div>
