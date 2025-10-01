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
                        <div class="px-4 py-4 card-body">
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-xl">
                                    <img src="{{ $user_logged->avatar }}" alt="{{ $user_logged->name }}">
                                </div>
                                <div class="ms-3 name">
                                    <h5 class="font-bold">{{ $user_logged->name }}</h5>
                                    <h6 class="mb-0 text-muted">{{ $user_logged->short_email }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h4>Recently Add</h4>
                        </div>
                        <div class="pb-4 card-content">
                            @forelse ($recently_adds as $recently_add)
                                <a href="{{ route('repository.show', ['repository' => $recently_add->slug]) }}">
                                    <div class="px-4 py-3 recent-message d-flex">
                                        <div class="avatar avatar-lg">
                                            <img src="{{ asset('assets/compiled/jpg/5.jpg') }}">
                                        </div>
                                        <div class="name ms-4">
                                            <h5 class="mb-1">{{ $recently_add->author }}</h5>
                                            <h6 class="mb-0 text-muted">{{ $recently_add->type }}</h6>
                                        </div>
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
                                        class='mt-3 font-bold btn btn-block btn-xl btn-outline-primary'>
                                        See more
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Latest Repositories</h4>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-lg">
                                            <thead>
                                                <tr class="text-center">
                                                    <th>Author</th>
                                                    <th>Title</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($latest_repositories as $latest_repository)
                                                    <tr>
                                                        <td class="col-3">
                                                            <a
                                                                href="{{ route('repository.show', ['repository' => $latest_repository->slug]) }}">
                                                                <div
                                                                    class="d-flex align-items-center justify-content-center">
                                                                    <div class="avatar avatar-md">
                                                                        <img src="./assets/compiled/jpg/2.jpg">
                                                                    </div>
                                                                    <p class="mb-0 font-bold ms-3">
                                                                        {{ $latest_repository->author }}</p>
                                                                </div>
                                                            </a>
                                                        </td>
                                                        <td class="col-auto">
                                                            <a
                                                                href="{{ route('repository.show', ['repository' => $latest_repository->slug]) }}">
                                                                <p class="mb-0 text-center">{{ $latest_repository->title }}
                                                                </p>
                                                            </a>
                                                        </td>

                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="2">
                                                            <h6 class="text-center">Repositories Not Found</h6>
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>
@endsection

@push('scripts')
    <script src="{{ asset('assets/extensions/apexcharts/apexcharts.min.js') }}"></script>
    <script>
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
            }, ],
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
