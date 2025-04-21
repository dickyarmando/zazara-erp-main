<?php

namespace App\Http\Controllers;

use App\Models\TrPurchase;
use App\Models\TrPurchaseNon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PayTableReportController extends Controller
{
    public function index(Request $request)
    {
        $start_date = $request->sd;
        $end_date = $request->ed;
        $search = $request->s;

        $purchaseTax = TrPurchase::leftJoin('ms_suppliers', 'ms_suppliers.id', '=', 'tr_purchase.supplier_id')
            ->select('tr_purchase.id', 'tr_purchase.number', 'tr_purchase.date', 'tr_purchase.supplier_id', 'ms_suppliers.company_name as supplier_name', 'tr_purchase.reference', 'tr_purchase.total', 'tr_purchase.payment', 'tr_purchase.rest', 'tr_purchase.notes', 'tr_purchase.is_payed', 'tr_purchase.is_status')
            ->addSelect(DB::raw('"Tax" as type'))
            ->whereNotNull('tr_purchase.approved_at')
            ->whereBetween('tr_purchase.date', [$start_date, $end_date]);

        $purchaseNonTax = TrPurchaseNon::leftJoin('ms_suppliers', 'ms_suppliers.id', '=', 'tr_purchase_non.supplier_id')
            ->select('tr_purchase_non.id', 'tr_purchase_non.number', 'tr_purchase_non.date', 'tr_purchase_non.supplier_id', 'ms_suppliers.company_name as supplier_name', 'tr_purchase_non.reference', 'tr_purchase_non.total', 'tr_purchase_non.payment', 'tr_purchase_non.rest', 'tr_purchase_non.notes', 'tr_purchase_non.is_payed', 'tr_purchase_non.is_status')
            ->addSelect(DB::raw('"Non" as type'))
            ->whereNotNull('tr_purchase_non.approved_at')
            ->whereBetween('tr_purchase_non.date', [$start_date, $end_date]);

        // Query summary
        $purchaseTaxSummary = TrPurchase::leftJoin('ms_suppliers', 'ms_suppliers.id', '=', 'tr_purchase.supplier_id')
            ->selectRaw('SUM(total) as total_payment, SUM(payment) as paid, SUM(rest) as unpaid')
            ->whereNotNull('tr_purchase.approved_at')
            ->whereBetween('tr_purchase.date', [$start_date, $end_date]);
        $purchaseNonTaxSummary = TrPurchaseNon::leftJoin('ms_suppliers', 'ms_suppliers.id', '=', 'tr_purchase_non.supplier_id')
            ->selectRaw('SUM(total) as total_payment, SUM(payment) as paid, SUM(rest) as unpaid')
            ->whereNotNull('tr_purchase_non.approved_at')
            ->whereBetween('tr_purchase_non.date', [$start_date, $end_date]);

        if (!empty($search)) {
            $purchaseTax->orWhere('number', 'like', "%" . $search . "%")->whereNotNull('tr_purchase.approved_at')->whereBetween('tr_purchase.date', [$start_date, $end_date]);
            $purchaseTax->orWhere('ms_suppliers.company_name', 'like', "%" . $search . "%")->whereNotNull('tr_purchase.approved_at')->whereBetween('tr_purchase.date', [$start_date, $end_date]);
            $purchaseTax->orWhere('total', 'like', "%" . $search . "%")->whereNotNull('tr_purchase.approved_at')->whereBetween('tr_purchase.date', [$start_date, $end_date]);
            $purchaseTax->orWhere('payment', 'like', "%" . $search . "%")->whereNotNull('tr_purchase.approved_at')->whereBetween('tr_purchase.date', [$start_date, $end_date]);
            $purchaseTax->orWhere('rest', 'like', "%" . $search . "%")->whereNotNull('tr_purchase.approved_at')->whereBetween('tr_purchase.date', [$start_date, $end_date]);

            $purchaseNonTax->orWhere('number', 'like', "%" . $search . "%")->whereNotNull('tr_purchase_non.approved_at')->whereBetween('tr_purchase_non.date', [$start_date, $end_date]);
            $purchaseNonTax->orWhere('ms_suppliers.company_name', 'like', "%" . $search . "%")->whereNotNull('tr_purchase_non.approved_at')->whereBetween('tr_purchase_non.date', [$start_date, $end_date]);
            $purchaseNonTax->orWhere('total', 'like', "%" . $search . "%")->whereNotNull('tr_purchase_non.approved_at')->whereBetween('tr_purchase_non.date', [$start_date, $end_date]);
            $purchaseNonTax->orWhere('payment', 'like', "%" . $search . "%")->whereNotNull('tr_purchase_non.approved_at')->whereBetween('tr_purchase_non.date', [$start_date, $end_date]);
            $purchaseNonTax->orWhere('rest', 'like', "%" . $search . "%")->whereNotNull('tr_purchase_non.approved_at')->whereBetween('tr_purchase_non.date', [$start_date, $end_date]);

            $purchaseTaxSummary->orWhere('number', 'like', "%" . $search . "%")->whereNotNull('tr_purchase.approved_at')->whereBetween('tr_purchase.date', [$start_date, $end_date]);
            $purchaseTaxSummary->orWhere('ms_suppliers.company_name', 'like', "%" . $search . "%")->whereNotNull('tr_purchase.approved_at')->whereBetween('tr_purchase.date', [$start_date, $end_date]);
            $purchaseTaxSummary->orWhere('total', 'like', "%" . $search . "%")->whereNotNull('tr_purchase.approved_at')->whereBetween('tr_purchase.date', [$start_date, $end_date]);
            $purchaseTaxSummary->orWhere('payment', 'like', "%" . $search . "%")->whereNotNull('tr_purchase.approved_at')->whereBetween('tr_purchase.date', [$start_date, $end_date]);
            $purchaseTaxSummary->orWhere('rest', 'like', "%" . $search . "%")->whereNotNull('tr_purchase.approved_at')->whereBetween('tr_purchase.date', [$start_date, $end_date]);

            $purchaseNonTaxSummary->orWhere('number', 'like', "%" . $search . "%")->whereNotNull('tr_purchase_non.approved_at')->whereBetween('tr_purchase_non.date', [$start_date, $end_date]);
            $purchaseNonTaxSummary->orWhere('ms_suppliers.company_name', 'like', "%" . $search . "%")->whereNotNull('tr_purchase_non.approved_at')->whereBetween('tr_purchase_non.date', [$start_date, $end_date]);
            $purchaseNonTaxSummary->orWhere('total', 'like', "%" . $search . "%")->whereNotNull('tr_purchase_non.approved_at')->whereBetween('tr_purchase_non.date', [$start_date, $end_date]);
            $purchaseNonTaxSummary->orWhere('payment', 'like', "%" . $search . "%")->whereNotNull('tr_purchase_non.approved_at')->whereBetween('tr_purchase_non.date', [$start_date, $end_date]);
            $purchaseNonTaxSummary->orWhere('rest', 'like', "%" . $search . "%")->whereNotNull('tr_purchase_non.approved_at')->whereBetween('tr_purchase_non.date', [$start_date, $end_date]);
        }

        $purchaseSummary = DB::query()
            ->selectRaw('SUM(total_payment) as total_payment, SUM(paid) as paid, SUM(unpaid) as unpaid')
            ->fromSub($purchaseTaxSummary->unionAll($purchaseNonTaxSummary), 'combined_summary')
            ->first();

        $purchases = $purchaseTax->union($purchaseNonTax);
        $purchases = $purchases->get();

        return view('livewire.exports.pay-table-report', compact('purchases', 'purchaseSummary', 'start_date', 'end_date'));
    }
}
