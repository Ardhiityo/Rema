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
                            @if ($meta_data->author->user->avatar)
                                <img src="{{ Storage::url($meta_data->author->user->avatar) }}"
                                    alt="{{ $meta_data->author->user->name }}" class="rounded-pill"
                                    style="max-width: 38px">
                            @else
                                -
                            @endif
                            <span>{{ $meta_data->author->user->name }}</span>
                        </h4>
                        <p>
                            <small>{{ $meta_data->author->nim }} | {{ $meta_data->author->studyProgram->name }}</small>
                        </p>
                    </div>
                    <h5 class="card-text">
                        {{ $meta_data->title }}
                    </h5>
                    <p>
                        <small>
                            @can('view', $meta_data)
                                <span class="mb-2 badge {{ $badge_status_class }}">
                                    {{ $badge_ucfirst }}
                                </span>
                                <br>
                            @endcan
                            {{ $meta_data->created_at }}
                        </small>
                    </p>
                    <div class="gap-3 d-flex">
                        @foreach ($meta_data->categories as $category)
                            <a href="{{ route('repository.read', [
                                'category_slug' => $category->slug,
                                'meta_data_slug' => $meta_data->slug,
                            ]) }}"
                                target="_blank" class="btn btn-info btn-sm">
                                <span>{{ $category->name }}</span>
                                <i class="bi bi-eye-fill"></i>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @hasrole('admin')
            <livewire:note-form :meta_data_id="$meta_data->id" />
        @endhasrole
        @can('view', $meta_data)
            <livewire:note-list :meta_data_id="$meta_data->id" />
        @endcan
    </section>
</div>
