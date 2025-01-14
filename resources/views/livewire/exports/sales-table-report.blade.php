<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Table</title>
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
    <h2 class="text-center" style="margin-bottom: 5px;text-transform: uppercase;text-decoration: underline;">Report Sales
        Order</h2>
    <h4 class="text-center" style="margin-top:0px;">Period : {{ $start_date }} s/d {{ $end_date }}</h4>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Sales Number</th>
                <th>Date</th>
                <th>Customer</th>
                <th>Product</th>
                <th>UoM</th>
                <th>Qty</th>
                <th>Rate</th>
                <th>Status</th>
                <th>Received</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($saless as $index => $sales)
                <tr>
                    <td class="text-center no-wrap">{{ $index + 1 }}</td>
                    <td class="text-center no-wrap">{{ $sales->number }}</td>
                    <td class="text-center no-wrap">{{ $sales->date }}</td>
                    <td>{{ $sales->customer_name }}</td>
                    <td>{{ $sales->product_name }}</td>
                    <td class="text-center no-wrap">{{ $sales->unit_name }}</td>
                    <td class="text-center no-wrap">{{ number_format($sales->qty, 0) }}</td>
                    <td class="no-wrap">{{ number_format($sales->rate, 2) }}</td>
                    <td class="text-center no-wrap">{{ $sales->is_status == '1' ? 'Approved' : 'Cancelled' }}</td>
                    <td class="text-center no-wrap">{{ $sales->is_payed == '1' ? 'Paid' : 'Unpaid' }}</td>
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
