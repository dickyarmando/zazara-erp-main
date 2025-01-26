<?php

namespace App\Http\Controllers\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ReportSalesIncentiveXlsx implements FromView
{
    protected $data;

    public function __construct(array $data) {
        $this->data = $data;
    }

    public function view(): View
    {
        return view('livewire.exports.sales-incentive-report-xslx', [
            'incentiveSales' => $this->data['incentiveSales'],
            'summaryIncentiveSales' => $this->data['summaryIncentiveSales'],
            'incentiveAmount' => $this->data['incentiveAmount'],
            'incentiveDetails' => $this->data['incentiveDetails'],
            'invoice_start_date' => $this->data['invoice_start_date'],
            'invoice_end_date' => $this->data['invoice_end_date']
        ]);
    }
}
// class ReportSalesIncentiveXlsx extends Controller
// {
//     public function index(Request $request)
//     {
//         $invoice_start_date = $request->isd;
//         $invoice_end_date = $request->ied;
//         $sales_username = $request->su;

//         $queryInvoices = TrInvoice::select(
//             'tr_invoices.date AS invoice_date',
//             'tr_invoices.number AS invoice_no',
//             'ms_customers.name AS customer_name',
//             'tr_invoices.notes',
//             DB::raw('COALESCE(tr_invoices.total - tr_invoices.ppn_amount, 0) AS total_selling_price'),
//             DB::raw('COALESCE(SUM(combined_purchases.total), 0) AS total_capital_price'),
//             DB::raw('COALESCE((tr_invoices.total - tr_invoices.ppn_amount) - SUM(combined_purchases.total), 0) AS total_margin')
//         )
//             ->join('tr_sales', 'tr_sales.id', '=', 'tr_invoices.sales_id')
//             ->join('ms_customers', 'ms_customers.id', '=', 'tr_sales.customer_id')
//             ->join('users', 'users.id', '=', 'tr_sales.sales_id')
//             ->leftJoinSub(
//                 DB::table(function ($unionQuery) {
//                     $unionQuery->from('tr_purchase')
//                         ->select('reference', DB::raw('COALESCE((total - ppn_amount), 0) AS total'))
//                         ->where('is_status', 1)
//                         ->whereNotNull('approved_at')
//                         ->unionAll(
//                             DB::table('tr_purchase_non')
//                                 ->select('reference', 'total')
//                                 ->where('is_status', 1)
//                                 ->whereNotNull('approved_at')
//                         );
//                 }, 'combined_purchases')
//                 ->select('reference', DB::raw('SUM(total) AS total'))
//                 ->groupBy('reference'),
//                 'combined_purchases', 'combined_purchases.reference', '=', 'tr_sales.number'
//             )
//             ->where('tr_invoices.is_status', 1)
//             ->where('users.username', $sales_username)
//             ->whereBetween('tr_invoices.date', [$invoice_start_date, $invoice_end_date])
//             ->groupBy('tr_invoices.number', 'tr_invoices.date', 'tr_invoices.total', 'tr_invoices.ppn_amount', 'ms_customers.name', 'tr_invoices.notes')
//             ->havingRaw('COALESCE(tr_invoices.total - SUM(combined_purchases.total), 0) > 0');

//         $queryInvoicesNon = TrInvoicesNon::select(
//             'tr_invoices_nons.date AS invoice_date',
//             'tr_invoices_nons.number AS invoice_no',
//             'ms_customers.name AS customer_name',
//             'tr_invoices_nons.notes',
//             DB::raw('COALESCE(tr_invoices_nons.total, 0) AS total_selling_price'),
//             DB::raw('COALESCE(SUM(combined_purchases.total), 0) AS total_capital_price'),
//             DB::raw('COALESCE(tr_invoices_nons.total - SUM(combined_purchases.total), 0) AS total_margin')
//         )
//             ->join('tr_sales_non', 'tr_sales_non.id', '=', 'tr_invoices_nons.sales_non_id')
//             ->join('ms_customers', 'ms_customers.id', '=', 'tr_sales_non.customer_id')
//             ->join('users', 'users.id', '=', 'tr_sales_non.sales_id')
//             ->leftJoinSub(
//                 DB::table(function ($unionQuery) {
//                     $unionQuery->from('tr_purchase')
//                         ->select('reference', DB::raw('COALESCE((total - ppn_amount), 0) AS total'))
//                         ->where('is_status', 1)
//                         ->whereNotNull('approved_at')
//                         ->unionAll(
//                             DB::table('tr_purchase_non')
//                                 ->select('reference', 'total')
//                                 ->where('is_status', 1)
//                                 ->whereNotNull('approved_at')
//                         );
//                 }, 'combined_purchases')
//                 ->select('reference', DB::raw('SUM(total) AS total'))
//                 ->groupBy('reference'),
//                 'combined_purchases', 'combined_purchases.reference', '=', 'tr_sales_non.number'
//             )
//             ->where('tr_invoices_nons.is_status', 1)
//             ->where('users.username', '=', $sales_username)
//             ->whereBetween('tr_invoices_nons.date', [$invoice_start_date, $invoice_end_date])
//             ->groupBy('tr_invoices_nons.number', 'tr_invoices_nons.date', 'tr_invoices_nons.total', 'ms_customers.name', 'tr_invoices_nons.notes')
//             ->havingRaw('COALESCE(tr_invoices_nons.total - SUM(combined_purchases.total), 0) > 0');

//         $incentiveSales = $queryInvoices->union($queryInvoicesNon)->get();
        
//         $summary = $incentiveSales->reduce(function ($carry, $item) {
//             $carry['totalSellingPrice'] += $item->total_selling_price;
//             $carry['totalCapitalPrice'] += $item->total_capital_price;
//             return $carry;
//         }, ['totalSellingPrice' => 0, 'totalCapitalPrice' => 0]);

//         $summaryIncentiveSales = $summary['totalSellingPrice'] - $summary['totalCapitalPrice'];

//         $incentiveDetails = MsInsentifSales::select('target_amount', 'up', 'down', 'users.name as sales_name')
//             ->join('users', 'users.id', '=', 'ms_insentif_sales.user_id')
//             ->where('users.username', '=', $sales_username)
//             ->where('ms_insentif_sales.is_status', '=', '1')
//             ->first();

//         if($incentiveDetails){
//             $incentiveAmount = $summaryIncentiveSales >= $incentiveDetails->target_amount
//                 ? $summaryIncentiveSales * ($incentiveDetails->up / 100)
//                 : $summaryIncentiveSales * ($incentiveDetails->down / 100);
//         }else{
//             $incentiveDetails = new stdClass();
//             $incentiveDetails->target_amount = 0;
//             $incentiveDetails->up = 0;
//             $incentiveDetails->down = 0;
//             $incentiveDetails->sales_name = User::select('name')->where('username', '=', $sales_username)->first()->name;
//             $incentiveAmount = 0;
//         }

//         // dd($incentiveAmount);
//         // dd($incentiveSales);
//         return Excel::download(new Export([
//             'incentiveSales' => $incentiveSales,
//             'summaryIncentiveSales' => $summaryIncentiveSales,
//             'incentiveAmount' => $incentiveAmount,
//             'incentiveDetails' => $incentiveDetails,
//             'invoice_start_date' => $invoice_start_date,
//             'invoice_end_date' => $invoice_end_date
//         ]), 'report-sales-incentive.xlsx');
//     }
// }
