@extends('layouts.app')

@section('content')
    <main>
        <div class="page-heading">
            <h3>Repository Statistics</h3>
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
                                    <img src="./assets/compiled/jpg/1.jpg" alt="Face 1">
                                </div>
                                <div class="ms-3 name">
                                    <h5 class="font-bold">John Duck</h5>
                                    <h6 class="mb-0 text-muted">@johnducky</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h4>Recent Added</h4>
                        </div>
                        <div class="pb-4 card-content">
                            <div class="px-4 py-3 recent-message d-flex">
                                <div class="avatar avatar-lg">
                                    <img src="./assets/compiled/jpg/4.jpg">
                                </div>
                                <div class="name ms-4">
                                    <h5 class="mb-1">Hank Schrader</h5>
                                    <h6 class="mb-0 text-muted">@johnducky</h6>
                                </div>
                            </div>
                            <div class="px-4 py-3 recent-message d-flex">
                                <div class="avatar avatar-lg">
                                    <img src="./assets/compiled/jpg/5.jpg">
                                </div>
                                <div class="name ms-4">
                                    <h5 class="mb-1">Dean Winchester</h5>
                                    <h6 class="mb-0 text-muted">@imdean</h6>
                                </div>
                            </div>
                            <div class="px-4 py-3 recent-message d-flex">
                                <div class="avatar avatar-lg">
                                    <img src="./assets/compiled/jpg/1.jpg">
                                </div>
                                <div class="name ms-4">
                                    <h5 class="mb-1">John Dodol</h5>
                                    <h6 class="mb-0 text-muted">@dodoljohn</h6>
                                </div>
                            </div>
                            <div class="px-4">
                                <button class='mt-3 font-bold btn btn-block btn-xl btn-outline-primary'>Start
                                    Conversation</button>
                            </div>
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
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Comment</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="col-3">
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar avatar-md">
                                                                <img src="./assets/compiled/jpg/5.jpg">
                                                            </div>
                                                            <p class="mb-0 font-bold ms-3">Si Cantik</p>
                                                        </div>
                                                    </td>
                                                    <td class="col-auto">
                                                        <p class="mb-0 ">Congratulations on your graduation!</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="col-3">
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar avatar-md">
                                                                <img src="./assets/compiled/jpg/2.jpg">
                                                            </div>
                                                            <p class="mb-0 font-bold ms-3">Si Ganteng</p>
                                                        </div>
                                                    </td>
                                                    <td class="col-auto">
                                                        <p class="mb-0 ">Wow amazing design! Can you make another
                                                            tutorial for
                                                            this design?</p>
                                                    </td>
                                                </tr>
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
