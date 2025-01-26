<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Order Reports</title>
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
    </style>
</head>

<body>
    <h2 class="text-center" style="margin-bottom: 5px;text-transform: uppercase;text-decoration: underline;">Purchase
        Order Reports</h2>
    <h4 class="text-center" style="margin-top:0px;">Period : {{ $start_date }} s/d {{ $end_date }}</h4>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Purchase Number</th>
                <th>Date</th>
                <th>Supplier</th>
                <th>Product</th>
                <th>UoM</th>
                <th>Qty</th>
                <th>Rate</th>
                <th>Status</th>
                <th>Payment</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($purchases as $index => $purchase)
                <tr>
                    <td class="text-center no-wrap">{{ $index + 1 }}</td>
                    <td class="text-center no-wrap">{{ $purchase->number }}</td>
                    <td class="text-center no-wrap">{{ $purchase->date }}</td>
                    <td>{{ $purchase->supplier_name }}</td>
                    <td>{{ $purchase->product_name }}</td>
                    <td class="text-center no-wrap">{{ $purchase->unit_name }}</td>
                    <td class="text-center no-wrap">{{ number_format($purchase->qty, 0) }}</td>
                    <td class="no-wrap">{{ number_format($purchase->rate, 2) }}</td>
                    <td class="text-center no-wrap">
                        @if ($purchase->is_status == '1')
                            @if (isset($purchase->approved_at))
                                Approved
                            @else
                                Waiting Approve
                            @endif
                        @else
                            Cancelled
                        @endif
                    </td>
                    <td class="text-center no-wrap">
                        @if ($purchase->is_payed == '1')
                            Paid
                        @else
                            @if ($purchase->payment > 0)
                                Being Paid
                            @else
                                Unpaid
                            @endif
                        @endif
                    </td>
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
