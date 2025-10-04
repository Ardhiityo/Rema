<div>
    <div class="page-title">
        <div class="row">
            <div class="order-last col-12 col-md-6 order-md-1">
                <h4>Study Programs</h4>
                <p class="text-subtitle text-muted">
                    All study programs data listed.
                </p>
            </div>
            <div class="order-first col-12 col-md-6 order-md-2">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('study-program.index') }}">Study Programs</a></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <livewire:study-program-form />
    <livewire:study-program-list />
</div>
