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
                    <div class="card-content">
                        <div class="card-body">
                            <div class="flex-column d-flex">
                                <h4 class="card-title">{{ $author }}</h4>
                                <p>
                                    <small>{{ $nim }} | {{ $study_program }}</small>
                                </p>
                            </div>
                            <p class="card-text">
                                {{ $title }}
                            </p>
                            <p>
                                <small> {{ $type }}</small>
                            </p>
                            <p>
                                <small> {{ $published_at }}</small>
                            </p>
                            <a href="{{ route('repository.read', ['repository' => $slug]) }}" target="_blank"
                                class="block btn btn-primary">
                                <i class="bi bi-eye-fill"></i>
                                View
                            </a>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </section>
</div>
