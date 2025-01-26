<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Incentive Reports</title>
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

        .text-right {
            text-align: right;
        }

        .justify-between {
            display: flex;
            justify-content: space-between
        }

        .no-wrap {
            white-space: nowrap;
        }
    </style>
</head>

<body>
    <h2 class="text-center" style="margin-bottom: 5px;text-transform: uppercase;text-decoration: underline;">Sales Incentive Reports</h2>
    <h4 class="text-center" style="margin-top:0px;">Period : {{ $invoice_start_date }} s/d {{ $invoice_end_date }}</h4>
    <h5 style="margin-top:0px;">
        Sales By {{ $incentiveDetails->sales_name }} <br />
        Target Rp {{ number_format($incentiveDetails->target_amount, 0) }} <br />
        Up {{ $incentiveDetails->up }}% Down {{ $incentiveDetails->down }}%
    </h5>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Invoice Date</th>
                <th>Customer</th>
                <th>Invoice No</th>
                <th>Capital Price</th>
                <th>Selling Price</th>
                <th>Margin</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($incentiveSales as $incentiveDetail)
                <tr>
                    <td class="text-center">{{ $loop->index + 1 }}</td>
                    <td class="text-center no-wrap">{{ $incentiveDetail->invoice_date }}</td>
                    <td class="text-center no-wrap">{{ $incentiveDetail->customer_name }}</td>
                    <td class="text-center no-wrap">{{ $incentiveDetail->invoice_no }}</td>
                    <td class="text-right no-wrap">
                        <div class="justify-between">
                            <span>Rp</span>
                            <span>{{ number_format($incentiveDetail->total_capital_price, 0) }}</span>
                        </div>
                    </td>
                    <td class="text-right no-wrap">
                        <div class="justify-between">
                            <span>Rp</span>
                            <span>{{ number_format($incentiveDetail->total_selling_price, 0) }}</span>
                        </div>
                    </td>
                    <td class="text-right no-wrap">
                        <div class="justify-between">
                            <span>Rp</span>
                            <span>{{ number_format($incentiveDetail->total_margin, 0) }}</span>
                        </div>
                    </td>
                    <td class="text-center no-wrap">{{ $incentiveDetail->notes }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5"></td>
                <th class="text-left">Total</th>
                <td class="text-right">
                    <div class="justify-between">
                        <span>Rp</span>
                        <span>{{ number_format($summaryIncentiveSales, 0) }}</span>
                    </div>
                </td>
                <td></td>
            </tr>
            <tr>
                <td colspan="5"></td>
                <th class="text-left">Incentive</th>
                <td class="text-right">
                    <div class="justify-between">
                        <span>Rp</span>
                        <span>{{ number_format($incentiveAmount, 0) }}</span>
                    </div>
                </td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <script>
        window.onload = function() {
            window.print();
            setTimeout("window.close();", 1000);
        };
    </script>
</body>

</html>
