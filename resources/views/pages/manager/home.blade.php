<?php
/**
 *Created by PhpStorm for eshagi
 *User: Vincent Guyo
 *Date: 9/25/2020
 *Time: 11:28 AM
 */

?>
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
                            <h4 class="header-title mb-4">{{date('Y')}} Applications Demographic Report</h4>

                            <div id="demographic_rep" class="apex-charts" dir="ltr"></div>
                        </div>
                    </div><!--end card-->
                </div>
            </div>

            <div class="row">
                <div class="col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <h3>${{$newLoansValue}}</h3>
                            <h5 class="header-title mb-4">New Loans Value</h5>
                            <h7>ZWL</h7>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <h3>${{$pendingLoansValue}}</h3>
                            <h5 class="header-title mb-4">Pending Loans Value</h5>
                            <h7>ZWL</h7>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <h3>${{$disbursedLoansValue}}</h3>
                            <h5 class="header-title mb-4">Disbursed Loans Value</h5>
                            <h7>ZWL</h7>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <h3>${{$paidBackLoansValue}}</h3>
                            <h5 class="header-title mb-4">Paid Back Loans Value</h5>
                            <h7>ZWL</h7>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <h3>{{$zimLoansToday}} Loans</h3>
                            <h5 class="header-title mb-4">ZIM Loans Processed Today</h5>
                            <h7>ZWL</h7>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <h3>{{$zimUsdLoansToday}} Loans</h3>
                            <h5 class="header-title mb-4">ZIM Loans Processed Today</h5>
                            <h7>USD</h7>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <h3>{{$zimMonthToDate}} Loans</h3>
                            <h5 class="header-title mb-4">ZIM Loans Month to date</h5>
                            <h7>ZWL</h7>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <h3>{{$zimUsdMonthToDate}} Loans</h3>
                            <h5 class="header-title mb-4">ZIM Loans Month to date</h5>
                            <h7>USD</h7>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <h3>{{$zamLoansToday}} Loans</h3>
                            <h5 class="header-title mb-4">ZAM Loans Processed Today</h5>
                            <h7>ZMK</h7>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <h3>{{$zamMonthToDate}} Loans</h3>
                            <h5 class="header-title mb-4">ZAM Loans Month to date</h5>
                            <h7>ZMK</h7>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <h3>{{$zimDeviceLoansToday}} Loans</h3>
                            <h5 class="header-title mb-4">ZIM Device Loans Today</h5>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <h3>{{$zimDeviceLoansMonthDate}}</h3>
                            <h5 class="header-title mb-4">ZIM Device Month to date</h5>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row">

                <div class="col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <h3>{{$merchantsCount}}</h3>
                            <h5 class="header-title mb-4">Merchant Accounts</h5>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <h3>{{$agentsCount}}</h3>
                            <h5 class="header-title mb-4">Agent Accounts</h5>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title">ZWL Loans For {{date('F Y')}}</h4>
                            Total: {{$cumLoans->count()}} Loans <br>
                            Revenue: ${{number_format($sum,2,'.',',')}} <br>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title">USD Loans For {{date('F Y')}}</h4>
                            Total: {{$cumUsdLoans->count()}} Loans <br>
                            Revenue: ${{number_format($usdSum,2,'.',',')}} <br>
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

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title mb-4">{{date('Y')}} ZWL Loan Average Turnaround Time (Hours)</h4>

                            <div id="avg_turn_around_time" class="apex-charts" dir="ltr"></div>
                        </div>
                    </div><!--end card-->
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title mb-4">{{date('Y')}} Commissions Distribution</h4>

                            <div id="line_chart_datalabel" class="apex-charts" dir="ltr"></div>
                        </div>
                    </div><!--end card-->
                </div>
            </div>

            <div class="row">
                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title mb-4">Top 10 Best Selling Products</h4>

                            <div id="pie_chart" class="apex-charts" dir="ltr"></div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title mb-4">Loan Types Distribution</h4>

                            <div id="donut_chart" class="apex-charts"  dir="ltr"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title mb-4">Top 10 Zambia Most Financed Products</h4>

                            <div id="most_financed_pie_chart" class="apex-charts" dir="ltr"></div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title mb-4">Top 10 Zimbabwe Most Financed Products</h4>

                            <div id="zim_pie_chart" class="apex-charts" dir="ltr"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title mb-4">Loans By Channel</h4>

                            <div id="channel_donut_chart" class="apex-charts"  dir="ltr"></div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title mb-4">USD Loans By Channel</h4>

                            <div id="usd_channel_donut_chart" class="apex-charts"  dir="ltr"></div>
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

    </script>

    <script>
        cData = {!! json_encode($commissionData) !!};
        var options = {
            chart: {height: 380, type: "line", zoom: {enabled: !1}, toolbar: {show: !1}},
            colors: ["#3c7f29"],
            dataLabels: {enabled: !0},
            stroke: {width: [3], curve: "straight"},
            series: [{name: "Total Commission", data: cData}],
            title: {text: "Paidout Agent Commissions", align: "left"},
            grid: {row: {colors: ["transparent", "transparent"], opacity: .2}, borderColor: "#f1f1f1"},
            markers: {style: "inverted", size: 6},
            xaxis: {categories: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
                title: {text: "Monthly - Agent Commissions By Month"}},
            legend: {position: "top", horizontalAlign: "right", floating: !0, offsetY: -25, offsetX: -5},
            responsive: [{breakpoint: 600, options: {chart: {toolbar: {show: !1}}, legend: {show: !1}}}]
        };
        (chart = new ApexCharts(document.querySelector("#line_chart_datalabel"), options)).render()
    </script>

    <script>
        var musvo = {!! json_encode($bestsellersData) !!};
        options = {
            chart: {height: 320, type: "pie"},
            series: Object.values(musvo),
            labels: Object.keys(musvo),
            colors: ["#3051d3", "#2fa97c", "#e4cc37", "#f06543", "#420dab"],
            legend: {
                show: 1,
                position: "bottom",
                horizontalAlign: "center",
                verticalAlign: "middle",
                floating: !1,
                fontSize: "12px",
                offsetX: 0,
                offsetY: 0
            },
            responsive: [{breakpoint: 600, options: {chart: {height: 240}, legend: {show: !1}}}]
        };
        (chart = new ApexCharts(document.querySelector("#pie_chart"), options)).render()
    </script>

    <script>
        var bestloans = {!! json_encode($bestloansData) !!};
        options = {
            chart: {height: 320, type: "donut"},
            series: Object.values(bestloans),
            labels: Object.keys(bestloans),
            colors: ["#3051d3", "#00a7e1", "#2fa97c", "#f06543"],
            legend: {
                show: !0,
                position: "bottom",
                horizontalAlign: "center",
                verticalAlign: "middle",
                floating: !1,
                fontSize: "14px",
                offsetX: 0,
                offsetY: 0
            },
            responsive: [{breakpoint: 600, options: {chart: {height: 240}, legend: {show: !1}}}]
        };
        (chart = new ApexCharts(document.querySelector("#donut_chart"), options)).render();

        zwlData = {!! json_encode($zwlLoansData) !!};
        zmkData = {!! json_encode($zmkLoansData) !!};
        devData = {!! json_encode($devLoansData) !!};
        usdData = {!! json_encode($usdLoansData) !!};
        var options = {
            chart: {height: 380, type: "line", zoom: {enabled: !1}, toolbar: {show: !1}},
            colors: ["#3c7f29","#ff0000","#1634cb","#ff9900"],
            dataLabels: {enabled: !0},
            stroke: {width: [3], curve: "straight"},
            series: [
                {name: "Zim Loans", data: zwlData},
                {name: "Zam Loans", data: zmkData},
                {name: "Device Loans", data: devData},
                {name: "USD Loans", data: usdData},
            ],
            title: {text: "Loan Applications", align: "left"},
            grid: {row: {colors: ["transparent", "transparent"], opacity: .2}, borderColor: "#f1f1f1"},
            markers: {style: "inverted", size: 6},
            xaxis: {categories: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
                title: {text: "Loan Applications"}},
            legend: {position: "top", horizontalAlign: "right", floating: !0, offsetY: -25, offsetX: -5},
            responsive: [{breakpoint: 600, options: {chart: {toolbar: {show: !1}}, legend: {show: !1}}}]
        };
        (chart = new ApexCharts(document.querySelector("#demographic_rep"), options)).render();

        cData = {!! json_encode($turnAroundData) !!};
        var options = {
            chart: {height: 380, type: "line", zoom: {enabled: !1}, toolbar: {show: !1}},
            colors: ["#3c7f29","#ff0000"],
            dataLabels: {enabled: !0},
            stroke: {width: [3], curve: "straight"},
            series: [{name: "AVG Turnaround time", data: cData}],
            title: {text: "Time (Hours)", align: "left"},
            grid: {row: {colors: ["transparent", "transparent"], opacity: .2}, borderColor: "#f1f1f1"},
            markers: {style: "inverted", size: 6},
            xaxis: {categories: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
                title: {text: "Monthly - Average Turn Around Time"}},
            legend: {position: "top", horizontalAlign: "right", floating: !0, offsetY: -25, offsetX: -5},
            responsive: [{breakpoint: 600, options: {chart: {toolbar: {show: !1}}, legend: {show: !1}}}]
        };
        (chart = new ApexCharts(document.querySelector("#avg_turn_around_time"), options)).render();

        var mostoFin = {!! json_encode($mostFinancedData) !!};
        options = {
            chart: {height: 320, type: "pie"},
            series: Object.values(mostoFin),
            labels: Object.keys(mostoFin),
            colors: ["#3051d3", "#2fa97c", "#e4cc37", "#f06543", "#420dab"],
            legend: {
                show: 1,
                position: "bottom",
                horizontalAlign: "center",
                verticalAlign: "middle",
                floating: !1,
                fontSize: "12px",
                offsetX: 0,
                offsetY: 0
            },
            responsive: [{breakpoint: 600, options: {chart: {height: 240}, legend: {show: !1}}}]
        };
        (chart = new ApexCharts(document.querySelector("#most_financed_pie_chart"), options)).render();

        var mostoZimFin = {!! json_encode($mostFinancedZimData) !!};
        options = {
            chart: {height: 320, type: "pie"},
            series: Object.values(mostoZimFin),
            labels: Object.keys(mostoZimFin),
            colors: ["#3051d3", "#2fa97c", "#e4cc37", "#f06543", "#420dab"],
            legend: {
                show: 1,
                position: "bottom",
                horizontalAlign: "center",
                verticalAlign: "middle",
                floating: !1,
                fontSize: "12px",
                offsetX: 0,
                offsetY: 0
            },
            responsive: [{breakpoint: 600, options: {chart: {height: 240}, legend: {show: !1}}}]
        };
        (chart = new ApexCharts(document.querySelector("#zim_pie_chart"), options)).render();

        var loansChannel = {!! json_encode($loanChannelsData) !!};
        options = {
            chart: {height: 320, type: "donut"},
            series: Object.values(loansChannel),
            labels: Object.keys(loansChannel),
            colors: ["#3051d3", "#00a7e1", "#2fa97c", "#f06543"],
            legend: {
                show: !0,
                position: "bottom",
                horizontalAlign: "center",
                verticalAlign: "middle",
                floating: !1,
                fontSize: "14px",
                offsetX: 0,
                offsetY: 0
            },
            responsive: [{breakpoint: 600, options: {chart: {height: 240}, legend: {show: !1}}}]
        };
        (chart = new ApexCharts(document.querySelector("#channel_donut_chart"), options)).render();

        var usdLoansChannel = {!! json_encode($usdLoanChannelsData) !!};
        options = {
            chart: {height: 320, type: "donut"},
            series: Object.values(usdLoansChannel),
            labels: Object.keys(usdLoansChannel),
            colors: ["#3051d3", "#00a7e1", "#2fa97c", "#f06543"],
            legend: {
                show: !0,
                position: "bottom",
                horizontalAlign: "center",
                verticalAlign: "middle",
                floating: !1,
                fontSize: "14px",
                offsetX: 0,
                offsetY: 0
            },
            responsive: [{breakpoint: 600, options: {chart: {height: 240}, legend: {show: !1}}}]
        };
        (chart = new ApexCharts(document.querySelector("#usd_channel_donut_chart"), options)).render();
    </script>
@endsection
