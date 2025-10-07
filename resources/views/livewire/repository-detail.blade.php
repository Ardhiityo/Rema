<div>
    <div class="page-title">
        <div class="row">
            <div class="order-last col-12 col-md-6 order-md-1">
                <h3>Metadata</h3>
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
        <div class="card">
            <div class="card-content">
                <div class="card-body">
                    <div class="mb-2 flex-column d-flex">
                        <h4 class="gap-2 card-title d-flex align-items-center">
                            @if ($avatar)
                                <img src="{{ $avatar }}" alt="{{ $author }}" class="rounded-pill"
                                    style="max-width: 38px">
                            @else
                                -
                            @endif
                            <span>{{ $author }}</span>
                        </h4>
                        <p>
                            <small>{{ $nim }} | {{ $study_program }}</small>
                        </p>
                    </div>
                    <h5 class="card-text">
                        {{ $title }}
                    </h5>
                    <p class="card-text">
                        {{ $abstract }}
                    </p>
                    <p>
                        <small>
                            @can('view', $repository)
                                <span class="{{ $badge_status }} mb-2">
                                    {{ $status }}
                                </span>
                                <br>
                            @endcan
                            {{ $created_at }}
                        </small>
                    </p>
                    <div class="gap-3 d-flex">
                        <a href="{{ route('repository.read', ['repository' => $slug]) }}" target="_blank"
                            class="btn btn-info btn-sm">
                            <span>{{ $category }}</span>
                            <i class="bi bi-eye-fill"></i>
                        </a>
                        <a href="" target="_blank" class="btn btn-secondary btn-sm">
                            <span>Journal</span>
                            <i class="bi bi-eye-fill"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @hasrole('admin')
            <livewire:note-form :repository_id="$repository_id" />
        @endhasrole
        @can('view', $repository)
            <livewire:note-list :repository_id="$repository_id" />
        @endcan
    </section>
</div>
