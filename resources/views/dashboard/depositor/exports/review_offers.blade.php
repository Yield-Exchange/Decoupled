<!doctype html>

<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Review Offers</title>
    <meta name="description" content="Review Offers">
    <meta name="author" content="Review Offers">
    <link href="{{ public_path('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
    <style>
        .table td, .table th{
            padding: .40rem 0.35rem!important;
        }
    </style>
</head>

<body>
<div id="printIt" class="container-fluid">
    <table class="table table-borderless table-condensed">
        <thead>
        <tr>
            <td>{{ $deposit_request->organization->name }}</td>
            <td>Date &amp; Time Printed: <?php echo date('Y-m-d h:i:s');?></td>
        </tr>
        <tr>
            <td colspan="2"></td>
        </tr>
        <tr>
            <td colspan="2">Request ID: {{ $deposit_request->reference_no }}</td>
        </tr>
        <tr>
            <td colspan="2"></td>
        </tr>
        <tr>
            <td colspan="2">
                Product: {{ $deposit_request->product->description }}
            </td>
        </tr>
        <tr>
            <td colspan="2"></td>
        </tr>
        <tr>
            <td colspan="2">Term:
                @if ($deposit_request->term_length_type == "HISA")
                    -
                @else
                    {{ $deposit_request->term_length.' '.ucwords(strtolower($deposit_request->term_length_type)) }}
                @endif
            </td>
        </tr>
        <tr>
            <td colspan="2"></td>
        </tr>
        <tr>
            <td colspan="2">Requested amount: {{ $deposit_request->currency.' '.$deposit_request->amount  }} </td>
        </tr>
        <tr>
            <td colspan="2"></td>
        </tr>
        <tr>
            <td colspan="2">Wtd. Avg. Interest:: {{ number_format($offers ? $offers->sum('interest_rate_offer')*100/max($offers->count(),1) : 0,3)  }}</td>
        </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
    <h3>All offers</h3>
    <table class="table table-bordered table-condensed">
        <thead>
        <tr>
            <th>Institution</th>
            <th>Interest Rate %</th>
            <th>Max Amount</th>
            <th>Min Amount</th>
        </tr>
        </thead>
        <tbody>
        @foreach($offers as $offer)
            <tr>
                <td>{{ $offer->invited->bank->name }}</td>
                <td>{{ formatInterest($offer->interest_rate_offer) }}</td>
                <td> {{ $deposit_request->currency.' '.$offer->maximum_amount  }} </td>
                <td> {{ $deposit_request->currency.' '.$offer->minimum_amount  }} </td>
            </tr>
        @endforeach
        </tbody>
    </table>

</div>
</body>
</html>