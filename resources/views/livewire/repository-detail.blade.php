<div>
    <div class="page-title">
        <div class="row">
            <div class="order-last col-12 col-md-6 order-md-1">
                <h3>Repositories</h3>
                <p class="text-subtitle text-muted">Detailed information about the metadata.</p>
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
                                <h3 class="card-title">Metadata</h3>
                                <div class="gap-2 mt-3 d-flex flex-column">
                                    <h5>
                                        {{ $author }}
                                    </h5>
                                    <p>
                                        <small>{{ $nim }} | {{ $study_program }}</small>
                                    </p>
                                </div>
                            </div>
                            <p class="card-text">
                                {{ $title }}
                            </p>
                            <p>
                                <small> {{ $category }} - {{ $created_at }}</small>
                            </p>
                            <a href="{{ route('repository.read', ['repository' => $slug]) }}" target="_blank"
                                class="block btn btn-primary">
                                <i class="bi bi-eye-fill"></i>
                                View
                            </a>
                        </div>
                    </div>
                </div>
                <div class="page-title">
                    <div class="row">
                        <div class="order-last col-12 col-md-6 order-md-1">
                            <h3>Notes</h3>
                            <p class="text-subtitle text-muted">Detailed information about the notes .</p>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <div class="flex-column d-flex">
                                <h5 class="card-title">{{ $created_at }}</h5>
                            </div>
                            <p class="mt-3 card-text">
                                {{ $title }}
                            </p>
                            @hasrole('admin')
                                <div class="gap-3 d-flex">
                                    <a href="" target="_blank" class="block btn btn-primary">
                                        <i class="bi bi-pencil-square"></i>
                                        Edit
                                    </a>
                                    <a href="" target="_blank" class="block btn btn-danger">
                                        <i class="bi bi-trash3"></i>
                                        Delete
                                    </a>
                                </div>
                            @endhasrole
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </section>
</div>
