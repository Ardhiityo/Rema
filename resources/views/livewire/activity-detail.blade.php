<div>
    <x-page-title :title="'Activities'" :content="'Detailed information about the activities.'" />

    @foreach ($this->activities as $activity)
        <div class="mb-3 card">
            <div class="card-content">
                <div class="card-body">
                    <div class="mb-2 flex-column d-flex">
                        <h4 class="gap-2 card-title d-flex align-items-center">
                            <img src="{{ $activity->avatar }}" alt="{{ $activity->name }}" class="rounded-pill"
                                style="max-width: 38px">
                            <span>{{ $activity->name }}</span>
                        </h4>
                        <p>
                            <small>{{ $activity->nim }} | {{ $activity->study_program }}</small>
                        </p>
                    </div>
                    <h5 class="card-text">
                        {{ $activity->ip }}
                    </h5>
                    <p>
                        <small>
                            <span class="mb-2 badge-primary ">
                                {{ $activity->user_agent }}
                            </span>
                        </small>
                    </p>
                    <p>
                        <small>
                            <strong>
                                {{ $activity->created_at }}
                            </strong>
                        </small>
                    </p>
                </div>
            </div>
        </div>
    @endforeach

    @if ($this->activities->total() > $this->activities->perPage())
        <div class="p-3 pt-4">
            {{ $this->activities->links() }}
        </div>
    @endif

</div>
