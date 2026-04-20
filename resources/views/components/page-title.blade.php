@props(['title'])

<div class="page-title">
    <div class="row">
        <div class="col-12">
            <h4>{{ $title }}</h4>
            <p class="text-subtitle text-muted">
                {{ $slot }}
            </p>
        </div>
    </div>
</div>
