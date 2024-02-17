@extends('layouts.app')

@section('template_title')
    Welcome {{ Auth::user()->name }}
@endsection

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title mb-1">Hello, {{auth()->user()->first_name}}</h4>
                </div>

                <div class="col-md-4">
                    <div class="float-right d-none d-md-block">
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="page-content-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="header-title mb-4">{{date('Y')}} My Yearly Performance - Loan Applications</h5>
                            <div id="yearly-loan-apps" class="apex-charts"></div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="row">
                <div class="col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <h3>{{$newLoans}}</h3>
                            <h5 class="header-title mb-4">New Loans</h5>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <h3>{{$pendingLoans}}</h3>
                            <h5 class="header-title mb-4">Pending Loans</h5>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <h3>{{$disbursedLoans}}</h3>
                            <h5 class="header-title mb-4">Disbursed Loans</h5>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <h3>{{$paidBackLoans}}</h3>
                            <h5 class="header-title mb-4">Paid Back Loans</h5>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <h3>${{$newLoansValue}}</h3>
                            <h5 class="header-title mb-4">New Loans Value</h5>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <h3>${{$pendingLoansValue}}</h3>
                            <h5 class="header-title mb-4">Pending Loans Value</h5>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <h3>${{$disbursedLoansValue}}</h3>
                            <h5 class="header-title mb-4">Disbursed Loans Value</h5>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <h3>${{$paidBackLoansValue}}</h3>
                            <h5 class="header-title mb-4">Paid Back Loans Value</h5>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="header-title mb-4">{{date('Y')}} Loans Disbursements By Month</h5>
                            <div id="yearly-sale-chart" class="apex-charts"></div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
@section('footer_scripts')
    <script>
        $(function () {
            $('[data-plugin="knob"]').knob()
        });
        musvo = {!! json_encode($cashLoansData) !!};
        chikwereti = {!! json_encode($creditLoansData) !!};
        var options = {
            chart: {height: 350, type: "area", toolbar: {show: 1}},
            colors: ["#3dff18", "#3c7f29"],
            dataLabels: {enabled: 1},
            series: [{name: "Cash Loans", data: musvo },
                {name: "Credit Loans", data: chikwereti}
            ],
            grid: {yaxis: {lines: {show: 1}}},
            stroke: {width: 3, curve: "stepline"},
            markers: {size: 0},
            xaxis: {
                categories: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
                title: {text: "Disbursed Loans By Month"}
            },
            fill: {type: "gradient", gradient: {shadeIntensity: 1, opacityFrom: .7, opacityTo: .9, stops: [0, 90, 100]}},
            legend: {position: "top", horizontalAlign: "right", floating: !0, offsetY: -25, offsetX: -5}
        };
        (chart = new ApexCharts(document.querySelector("#yearly-sale-chart"), options)).render();

        yearlyLoans = {!! json_encode($yearlyLoansData) !!};
        var options = {
            chart: {height: 350, type: "area", toolbar: {show: 1}},
            colors: ["#3dff18", "#3c7f29"],
            dataLabels: {enabled: 1},
            series: [{name: "Loans Value", data: yearlyLoans },
            ],
            grid: {yaxis: {lines: {show: 1}}},
            stroke: {width: 3, curve: "stepline"},
            markers: {size: 0},
            xaxis: {
                categories: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
                title: {text: "Loan Applications By Month"}
            },
            fill: {type: "gradient", gradient: {shadeIntensity: 1, opacityFrom: .7, opacityTo: .9, stops: [0, 90, 100]}},
            legend: {position: "top", horizontalAlign: "right", floating: !0, offsetY: -25, offsetX: -5}
        };
        (chart = new ApexCharts(document.querySelector("#yearly-loan-apps"), options)).render();


    </script>
@endsection

