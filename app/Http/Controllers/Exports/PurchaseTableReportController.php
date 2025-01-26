<?php

namespace App\Http\Controllers\Exports;

use App\Http\Controllers\Controller;
use App\Models\TrPurchase;
use App\Models\TrPurchaseNon;
use Illuminate\Http\Request;

class PurchaseTableReportController extends Controller
{
    public function index(Request $request)
    {
        $start_date = $request->sd;
        $end_date = $request->ed;
        $number = $request->n;
        $supplier = $request->s;
        $product = $request->p;

        $purchaseTax = TrPurchase::leftJoin('ms_suppliers', 'ms_suppliers.id', '=', 'tr_purchase.supplier_id')
            ->join('tr_purchase_details', 'tr_purchase.id', '=', 'tr_purchase_details.purchase_id')
            ->select('tr_purchase.id', 'tr_purchase.number', 'tr_purchase.date', 'tr_purchase.supplier_id', 'ms_suppliers.company_name as supplier_name', 'tr_purchase.payment', 'tr_purchase.approved_at', 'tr_purchase.approved_by', 'tr_purchase.is_payed', 'tr_purchase.is_status', 'tr_purchase_details.product_name', 'tr_purchase_details.unit_name', 'tr_purchase_details.qty', 'tr_purchase_details.rate', 'tr_purchase_details.amount')
            ->whereBetween('tr_purchase.date', [$start_date, $end_date]);

        $purchaseNonTax = TrPurchaseNon::leftJoin('ms_suppliers', 'ms_suppliers.id', '=', 'tr_purchase_non.supplier_id')
            ->join('tr_purchase_non_details', 'tr_purchase_non.id', '=', 'tr_purchase_non_details.purchase_non_id')
            ->select('tr_purchase_non.id', 'tr_purchase_non.number', 'tr_purchase_non.date', 'tr_purchase_non.supplier_id', 'ms_suppliers.company_name as supplier_name', 'tr_purchase_non.payment', 'tr_purchase_non.approved_at', 'tr_purchase_non.approved_by', 'tr_purchase_non.is_payed', 'tr_purchase_non.is_status', 'tr_purchase_non_details.product_name', 'tr_purchase_non_details.unit_name', 'tr_purchase_non_details.qty', 'tr_purchase_non_details.rate', 'tr_purchase_non_details.amount')
            ->whereBetween('tr_purchase_non.date', [$start_date, $end_date]);

        if (!empty($number)) {
            $purchaseTax->where('tr_purchase.number', 'like', "%" . $number . "%");

            $purchaseNonTax->where('tr_purchase_non.number', 'like', "%" . $number . "%");
        }

        if (!empty($customer)) {
            $purchaseTax->where('ms_suppliers.company_name', 'like', "%" . $customer . "%");

            $purchaseNonTax->where('ms_suppliers.company_name', 'like', "%" . $customer . "%");
        }

        if (!empty($product)) {
            $purchaseTax->where('tr_purchase_details.product_name', 'like', "%" . $product . "%");

            $purchaseNonTax->where('tr_purchase_non_details.product_name', 'like', "%" . $product . "%");
        }

        $unionPurchase = $purchaseTax->union($purchaseNonTax);
        $purchases = $unionPurchase->get();

        return view('livewire.exports.purchase-table-report', compact('purchases', 'start_date', 'end_date'));
    }
}
