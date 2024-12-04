<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Reports</title>
    <style>
        table td,table th{
            width: 20%;
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        div,table{
            width: 100%;
        }
        table{
            /* font-family: Arial, Helvetica, sans-serif; */
            border-collapse: collapse;
        }
    </style>
</head>

<body>
<div>
    <h3>Reports</h3>
    <table>
        <thead>
        <tr>
            <th>Contract Number</th>
            <th>GIC Number</th>
            <th>Bank Name</th>
            <th>Product</th>
            <th>Term</th>
            <th>Lockout/Notice Period</th>
            <th>Amount</th>
            <th>Interest Rate</th>
            <th>Start Date</th>
            <th>Maturity Date</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($data as $datum)
        <tr>
            <td>{{ $datum->reference_no }}</td>
            <td>{{ $datum->gic_number }}</td>
            <td>{{ $datum->bank_name }}</td>
            <td>{{ $datum->product_name }}</td>
            <td>{{ $datum->term_length_type == "HISA" ? "-" : $datum->term_length. ' ' . ucwords(strtolower($datum->term_length_type)) }}</td>
            <td>{{ $datum->lockout_period_days ? $datum->lockout_period_days : '-' }}</td>
            <td>{{ $datum->currency.' '.number_format($datum->amount) }}</td>
            <td>{{ formatInterest($datum->interest_rate_offer) }}</td>
            <td>{{ $datum->gic_start_date ? changeDateFromUTCtoLocal($datum->gic_start_date,App\Constants::DATE_FORMAT): '-' }}</td>
            <td>{{ $datum->maturity_date ? changeDateFromUTCtoLocal($datum->maturity_date,App\Constants::DATE_FORMAT) : '-' }}</td>
        </tr>
        @endforeach
        </tbody>
    </table>

</div>
</body>
</html>