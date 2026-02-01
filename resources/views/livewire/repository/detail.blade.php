<div>
    <x-page-title :title="'Metadata'" :content="'Detailed information about the metadata.'" />

    <div class="card">
        <div class="card-content">
            <div class="card-body">
                <div class="mb-2 flex-column d-flex">
                    <h4 class="gap-2 card-title d-flex align-items-center">
                        <img src="{{ $avatar }}" alt="{{ $meta_data->author_name }}" class="rounded-pill"
                            style="max-width: 38px">
                        <span>{{ $meta_data->author_name }}</span>
                    </h4>
                    <p>
                        <small>{{ $meta_data->author_nim }} | {{ $study_program }}</small>
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
                        Year of Graduation <strong>{{ $meta_data->year }}</strong>
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
        <livewire:note.note-form :meta_data_id="$meta_data->id" />
    @endhasrole
    @can('view', $meta_data)
        <livewire:note.note-list :meta_data_id="$meta_data->id" />
    @endcan

</div>
