<div>
    <x-page-title :title="'Metadata'">
        Detailed information about the metadata.
    </x-page-title>

    <div class="card">
        <div class="card-content">
            <div class="card-body">
                <div class="flex-column d-flex">
                    <h4 class="gap-2 card-title d-flex align-items-center">
                        <div class="avatar avatar-lg">
                            <img src="{{ $avatar }}" alt="avatar">
                        </div>
                        <span>{{ $meta_data->author_name }}</span>
                    </h4>
                    <p class="mb-0">
                        <small>{{ $meta_data->author_nim }} | {{ $study_program }}</small>
                    </p>
                </div>
                <p class="mt-3">
                    <small>
                        @can('view', $meta_data)
                            <span class="badge {{ $badge_status_class }}">
                                {{ $badge_ucfirst }}
                            </span>
                            <br>
                        @endcan
                    </small>
                </p>
                <h5 class="mb-0">
                    {{ $meta_data->title }}
                </h5>
                <p>
                    <small>
                        Year of Graduation <strong>{{ $meta_data->year }}</strong> <br>
                        @foreach ($meta_data->keywords as $keyword)
                            <span class="badge bg-primary me-1">
                                #{{ $keyword->name }}
                            </span>
                        @endforeach
                    </small>
                </p>
                <div class="gap-3 mt-4 d-flex">
                    @foreach ($meta_data->categories as $category)
                                        <a href="{{ route('repository.read', [
                            'category_slug' => $category->slug,
                            'meta_data_slug' => $meta_data->slug,
                        ]) }}" target="_blank" class="btn btn-info btn-sm">
                                            <span>{{ $category->name }}</span>
                                            <i class="bi bi-eye-fill"></i>
                                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    @hasrole('admin')
    <livewire:note.note-form :meta_data_id="$meta_data->id" />
    @endhasrole
    @can('view', $meta_data)
        <livewire:note.note-list :meta_data_id="$meta_data->id" />
    @endcan

</div>