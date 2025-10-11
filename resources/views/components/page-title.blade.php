@props(['title', 'content'])

<div class="page-title">
    <div class="row">
        <div class="order-last col-12 col-md-6 order-md-1">
            <h4>{{ $title }}</h4>
            <p class="text-subtitle text-muted">
                {{ $content }}
            </p>
        </div>
    </div>
</div>
