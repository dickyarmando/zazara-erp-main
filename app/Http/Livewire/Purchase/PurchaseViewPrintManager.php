<?php

namespace App\Http\Livewire\Purchase;

use App\Http\Controllers\Controller;
use App\Models\MsSuppliers;
use App\Models\PrmCompanies;
use App\Models\TrPurchase;
use App\Models\TrPurchaseDetails;

class PurchaseViewPrintManager extends Controller
{
    public function index($id)
    {
        $company = PrmCompanies::find(1);
        $purchase = TrPurchase::find($id);
        $purchaseDetails = TrPurchaseDetails::where('purchase_id', $purchase->id)
            ->select('id', 'product_name as name', 'unit_name as unit', 'qty', 'rate as price', 'amount as total')
            ->get()->toArray();
        $suppliers = MsSuppliers::find($purchase->supplier_id);

        return view('livewire.purchase.purchase-view-print-manager', ['purchase' => $purchase, 'items' => $purchaseDetails, 'companies' => $company, 'suppliers' => $suppliers]);
    }
}
