@extends('layouts.app')

@section('content')
    <main>
        <div class="page-heading">
            <h4>Hello, {{ $user_logged->name }}</h4>
        </div>
        <div class="page-content">
            <section class="row">
                <div class="col-12 col-lg-9">
                    <livewire:metric-card />
                    <x-metric-chart />
                </div>
                <div class="col-12 col-lg-3">
                    <div class="card">
                        <div class="px-4 py-3 card-body">
                            <div class="row" style="height: 120px">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-xl">
                                        <img src="{{ $user_logged->avatar }}" alt="{{ $user_logged->name }}">
                                    </div>
                                    <div class="mt-2 ms-3 name text-break">
                                        <h5 class="font-bold">{{ $user_logged->short_name }}</h5>
                                        <h6 class="text-muted">{{ $user_logged->short_email }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h4>Recently Add</h4>
                        </div>
                        <div class="pb-3 card-content">
                            @forelse ($recently_adds as $recently_add)
                                <a href="{{ route('repository.show', ['meta_data' => $recently_add->slug]) }}">
                                    <div class="gap-2 px-4 py-2 d-flex align-items-center">
                                        <div class="avatar avatar-lg">
                                            <img src="{{ $recently_add->avatar }}">
                                        </div>
                                        <h6 title="{{ $recently_add->name }}">
                                            {{ $recently_add->short_name }}
                                        </h6>
                                    </div>
                                </a>
                            @empty
                                <div class="px-4 py-3 recent-message d-flex justify-content-center">
                                    <h6>Author Not Found</h5>
                                </div>
                            @endforelse
                            @if ($recently_adds->isNotEmpty())
                                <div class="px-4">
                                    <a href="{{ route('repository.index') }}"
                                        class='mt-2 font-bold btn btn-block btn-xl btn-outline-primary'>
                                        See more
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>
@endsection

@push('scripts')
    <script src="{{ asset('assets/extensions/apexcharts/apexcharts.min.js') }}" @cspNonce></script>
    <script @cspNonce>
        var optionsProfileVisit = {
            annotations: {
                position: "back",
            },
            dataLabels: {
                enabled: true,
            },
            chart: {
                type: "bar",
                height: 300,
            },
            fill: {
                opacity: 1,
            },
            plotOptions: {},
            series: [{
                name: "repository",
                data: @json($repository_totals),
            },],
            colors: "#435ebe",
            xaxis: {
                categories: @json($repository_years),
            },
        }

        var chartProfileVisit = new ApexCharts(
            document.querySelector("#chart-profile-visit"),
            optionsProfileVisit
        )

        chartProfileVisit.render()
    </script>
@endpush