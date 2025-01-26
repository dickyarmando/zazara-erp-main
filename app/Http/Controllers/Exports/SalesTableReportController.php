<?php

namespace App\Http\Controllers\Exports;

use App\Http\Controllers\Controller;
use App\Models\TrSales;
use App\Models\TrSalesNon;
use Illuminate\Http\Request;

class SalesTableReportController extends Controller
{
    public function index(Request $request)
    {
        $start_date = $request->sd;
        $end_date = $request->ed;
        $number = $request->n;
        $customer = $request->c;
        $product = $request->p;

        $salesTax = TrSales::leftJoin('ms_customers', 'ms_customers.id', '=', 'tr_sales.customer_id')
            ->join('tr_sales_details', 'tr_sales.id', '=', 'tr_sales_details.sales_id')
            ->select('tr_sales.id', 'tr_sales.number', 'tr_sales.date', 'ms_customers.company_name as customer_name', 'tr_sales.approved_at', 'tr_sales.approved_by', 'tr_sales.is_invoice', 'tr_sales.payment', 'tr_sales.is_receive', 'tr_sales.is_status', 'tr_sales_details.product_code', 'tr_sales_details.product_name', 'tr_sales_details.unit_name', 'tr_sales_details.qty', 'tr_sales_details.rate', 'tr_sales_details.amount')
            ->whereBetween('tr_sales.date', [$start_date, $end_date]);

        $salesNonTax = TrSalesNon::leftJoin('ms_customers', 'ms_customers.id', '=', 'tr_sales_non.customer_id')
            ->join('tr_sales_non_details', 'tr_sales_non.id', '=', 'tr_sales_non_details.sales_non_id')
            ->select('tr_sales_non.id', 'tr_sales_non.number', 'tr_sales_non.date', 'ms_customers.company_name as customer_name', 'tr_sales_non.approved_at', 'tr_sales_non.approved_by', 'tr_sales_non.is_invoice', 'tr_sales_non.payment', 'tr_sales_non.is_receive', 'tr_sales_non.is_status', 'tr_sales_non_details.product_code', 'tr_sales_non_details.product_name', 'tr_sales_non_details.unit_name', 'tr_sales_non_details.qty', 'tr_sales_non_details.rate', 'tr_sales_non_details.amount')
            ->whereBetween('tr_sales_non.date', [$start_date, $end_date]);

        if (!empty($number)) {
            $salesTax->where('tr_sales.number', 'like', "%" . $number . "%");

            $salesNonTax->where('tr_sales_non.number', 'like', "%" . $number . "%");
        }

        if (!empty($customer)) {
            $salesTax->where('ms_customers.company_name', 'like', "%" . $customer . "%");

            $salesNonTax->where('ms_customers.company_name', 'like', "%" . $customer . "%");
        }

        if (!empty($product)) {
            $salesTax->where('tr_sales_details.product_name', 'like', "%" . $product . "%");

            $salesNonTax->where('tr_sales_non_details.product_name', 'like', "%" . $product . "%");
        }

        $unionSales = $salesTax->union($salesNonTax);
        $saless = $unionSales->get();

        return view('livewire.exports.sales-table-report', compact('saless', 'start_date', 'end_date'));
    }
}
