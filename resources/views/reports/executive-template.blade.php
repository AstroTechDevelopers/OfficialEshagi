<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
        <!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-Y996J4MKXZ"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-Y996J4MKXZ');
</script>
    <meta charset="utf-8">
</head>

<h4 class="header-title">Daily Sales Executive Summary For {{date('d F Y')}}</h4>
<table>
    <thead>
        <tr>
            <th>Total</th>
            <th>Worth ($)</th>
            <th>Average Loan Amount ($)</th>
        </tr>
    </thead>
    <tbody>
    @foreach($dailySales as $sales)
        <tr>
            <td>{{ $sales->Count }}</td>
            <td>{{ number_format($sales->Total,2,'.',',') }}</td>
            <td>{{ number_format($sales->Average,2,'.',',') }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<h4 class="header-title">Month to Date Executive Summary For {{date('F Y')}}</h4>
<table>
    <thead>
        <tr>
            <th>Total</th>
            <th>Worth ($)</th>
            <th>Average Loan Amount ($)</th>
        </tr>
    </thead>
    <tbody>
    @foreach($monthToDateLoans as $sales)
        <tr>
            <td>{{ $sales->Count }}</td>
            <td>{{ number_format($sales->Total,2,'.',',') }}</td>
            <td>{{ number_format($sales->Average,2,'.',',') }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<h4 class="header-title">Year to Date Executive Summary For {{date('Y')}}</h4>
<table>
    <thead>
        <tr>
            <th>Total</th>
            <th>Worth ($)</th>
            <th>Average Loan Amount ($)</th>
        </tr>
    </thead>
    <tbody>
    @foreach($yearToDateLoans as $sales)
        <tr>
            <td>{{ $sales->Count }}</td>
            <td>{{ number_format($sales->Total,2,'.',',') }}</td>
            <td>{{ number_format($sales->Average,2,'.',',') }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<h4 class="header-title">Disbursed Month to Date Executive Summary For {{date('F Y')}}</h4>
<table>
    <thead>
        <tr>
            <th>Total</th>
            <th>Worth ($)</th>
            <th>Average Loan Amount ($)</th>
        </tr>
    </thead>
    <tbody>
    @foreach($disbursedMonthToDateLoans as $sales)
        <tr>
            <td>{{ $sales->Count }}</td>
            <td>{{ number_format($sales->Total,2,'.',',') }}</td>
            <td>{{ number_format($sales->Average,2,'.',',') }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<h4 class="header-title">Cumulative Interest Revenue for {{date('F Y')}}</h4>
<div class="row">
    <div class="col-xl-12">
        <div class="col-xl-3">
            <div class="card-body">
                <h4 class="header-title">Loans For {{date('F Y')}}</h4>
                Total: {{$cumLoans->count()}} Loans <br>
                Revenue: ${{number_format($sum,2,'.',',')}} <br>
            </div>
        </div>
        <div class="col-xl-3">
            <div class="card-body">
                <h4 class="header-title">USD Loans For {{date('F Y')}}</h4>
                Total: {{$usdLoans->count()}} Loans <br>
                Revenue: ${{number_format($usdSum,2,'.',',')}} <br>
            </div>
        </div>
        <div class="col-xl-3">
            <div class="card-body">
                <h4 class="header-title">Device Loans For {{date('F Y')}}</h4>
                Total: {{$devLoans->count()}} Loans <br>
                Revenue: ${{number_format($devSum,2,'.',',')}} <br>
            </div>
        </div>
        <div class="col-xl-3">
            <div class="card-body">
                <h4 class="header-title">Zambia Loans For {{date('F Y')}}</h4>
                Total: {{$zamLoans->count()}} Loans <br>
                Revenue: ${{number_format($zamSum,2,'.',',')}} <br>
            </div>
        </div>
    </div>
</div>

</html>
