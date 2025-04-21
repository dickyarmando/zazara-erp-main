<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receive Reports</title>
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
    <h2 class="text-center" style="margin-bottom: 5px;text-transform: uppercase;text-decoration: underline;">Receive
        Reports</h2>
    <h4 class="text-center" style="margin-top:0px;">Period : {{ $start_date }} s/d {{ $end_date }}</h4>
    <table class="summary" cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th>Invoice Order</th>
                <th>: {{ number_format(count($saless)) }}</th>
                <th>Total Amount</th>
                <th>: Rp. {{ number_format($salesSummary->total_payment, 2) }}</th>
            </tr>
            <tr>
                <th>Total Paid</th>
                <th>: Rp. {{ number_format($salesSummary->paid, 2) }}</th>
                <th>Total Unpaid</th>
                <th>: Rp. {{ number_format($salesSummary->unpaid, 2) }}</th>
            </tr>
        </thead>
    </table>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Invoice Number</th>
                <th>Sales Number</th>
                <th>Date</th>
                <th>Customer</th>
                <th>Total</th>
                <th>Paid</th>
                <th>Rest</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($saless as $index => $sales)
                <tr>
                    <td class="text-center no-wrap">{{ $index + 1 }}</td>
                    <td class="text-center no-wrap">{{ $sales->number }}</td>
                    <td class="text-center no-wrap">{{ $sales->invoice_number }}</td>
                    <td class="text-center no-wrap">{{ $sales->date }}</td>
                    <td>{{ $sales->customer_name }}</td>
                    <td class="no-wrap">{{ number_format($sales->total, 2) }}</td>
                    <td class="no-wrap">{{ number_format($sales->payment, 2) }}</td>
                    @if ($sales->payment == 0)
                        <td class="no-wrap">{{ number_format($sales->total, 2) }}</td>
                    @else
                        <td class="no-wrap">{{ number_format($sales->rest, 2) }}</td>
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
