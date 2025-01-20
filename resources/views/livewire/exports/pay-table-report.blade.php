<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Reports</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 5px;
            font-size: 11pt;
        }

        th {
            background-color: #f4f4f4;
            font-size: 12pt;
            text-transform: capitalize;
            padding: 8px;
        }

        .text-center {
            text-align: center;
        }

        .no-wrap {
            white-space: nowrap;
        }

        table.summary th,
        table.summary td {
            border: 1px solid #fff;
            padding: 5px;
            font-size: 11pt;
            text-align: left;
        }
    </style>
</head>

<body>
    <h2 class="text-center" style="margin-bottom: 5px;text-transform: uppercase;text-decoration: underline;">Payment
        Reports</h2>
    <h4 class="text-center" style="margin-top:0px;">Period : {{ $start_date }} s/d {{ $end_date }}</h4>
    <table class="summary" cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th>Purchase Order</th>
                <th>: {{ number_format(count($purchases)) }}</th>
                <th>Total Amount</th>
                <th>: Rp. {{ number_format($purchaseSummary->total_payment, 2) }}</th>
            </tr>
            <tr>
                <th>Total Paid</th>
                <th>: Rp. {{ number_format($purchaseSummary->paid, 2) }}</th>
                <th>Total Unpaid</th>
                <th>: Rp. {{ number_format($purchaseSummary->unpaid, 2) }}</th>
            </tr>
        </thead>
    </table>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Purchase Number</th>
                <th>Date</th>
                <th>Supplier</th>
                <th>Total</th>
                <th>Paid</th>
                <th>Rest</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($purchases as $index => $purchase)
                <tr>
                    <td class="text-center no-wrap">{{ $index + 1 }}</td>
                    <td class="text-center no-wrap">{{ $purchase->number }}</td>
                    <td class="text-center no-wrap">{{ $purchase->date }}</td>
                    <td>{{ $purchase->supplier_name }}</td>
                    <td class="no-wrap">{{ number_format($purchase->total, 2) }}</td>
                    <td class="no-wrap">{{ number_format($purchase->payment, 2) }}</td>
                    @if ($purchase->payment == 0)
                        <td class="no-wrap">{{ number_format($purchase->total, 2) }}</td>
                    @else
                        <td class="no-wrap">{{ number_format($purchase->rest, 2) }}</td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>

    <script>
        window.onload = function() {
            window.print();
            setTimeout("window.close();", 1000);
        };
    </script>
</body>

</html>
