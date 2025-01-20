<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expanse Reports</title>
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
    <h2 class="text-center" style="margin-bottom: 5px;text-transform: uppercase;text-decoration: underline;">Expanse
        Reports</h2>
    <h4 class="text-center" style="margin-top:0px;">Period : {{ $start_date }} s/d {{ $end_date }}</h4>
    <table class="summary" cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th width="25%">Expanse</th>
                <th width="25%">: {{ number_format(count($generalLedgers)) }}</th>
                <th width="25%">Total Amount</th>
                <th width="25%">: Rp. {{ number_format($summaryGL->total_debit, 2) }}</th>
            </tr>
        </thead>
    </table>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Expanse Number</th>
                <th>Date</th>
                <th>Summary</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($generalLedgers as $index => $gl)
                <tr>
                    <td class="text-center no-wrap">{{ $index + 1 }}</td>
                    <td class="border-start text-center">{{ $gl->number }}</td>
                    <td class="border-start text-center">{{ $gl->date }}</td>
                    <td class="border-start">{{ $gl->notes }}</td>
                    <td class="border-start text-right">{{ number_format($gl->total_debit, 2) }}</td>
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
