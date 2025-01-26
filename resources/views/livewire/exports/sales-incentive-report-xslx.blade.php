<table>
    <thead>
        <tr>
            <td colspan="8">Sales Incentive Report Period : {{ $invoice_start_date }} s/d {{ $invoice_end_date }}</td>
        </tr>
        <tr>
            <td colspan="8">Sales By {{ $incentiveDetails->sales_name }}<td>
        </tr>
        <tr>
            <td colspan="8">Target Rp {{ number_format($incentiveDetails->target_amount, 0) }}<td>
        </tr>
        <tr>
            <td colspan="8">Up {{ $incentiveDetails->up }}% Down {{ $incentiveDetails->down }}%<td>
        </tr>
        <tr></tr>
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
            <td>{{ $loop->index + 1 }}</td>
            <td>{{ $incentiveDetail->invoice_date }}</td>
            <td>{{ $incentiveDetail->customer_name }}</td>
            <td>{{ $incentiveDetail->invoice_no }}</td>
            <td>{{ $incentiveDetail->total_capital_price }}</td>
            <td>{{ $incentiveDetail->total_selling_price }}</td>
            <td>{{ $incentiveDetail->total_margin }}</td>
            <td>{{ $incentiveDetail->notes }}</td>
        </tr>
    @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>Total</td>
            <td>{{ $summaryIncentiveSales }}</td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>Incentive</td>
            <td>{{ $incentiveAmount }}</td>
            <td></td>
        </tr>
    </tfoot>
</table>