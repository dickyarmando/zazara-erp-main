<?php

namespace App\Http\Livewire\Purchase;

use App\Http\Controllers\Controller;
use App\Models\MsSuppliers;
use App\Models\PrmCompanies;
use App\Models\PrmConfig;
use App\Models\TrPurchaseNon;
use App\Models\TrPurchaseNonDetails;

class PurchaseNonViewPrintManager extends Controller
{
    public function index($id)
    {
        $company = PrmCompanies::find(1);
        $purchase = TrPurchaseNon::find($id);
        $purchaseDetails = TrPurchaseNonDetails::where('purchase_non_id', $purchase->id)
            ->select('id', 'product_name as name', 'unit_name as unit', 'qty', 'rate as price', 'amount as total')
            ->get()->toArray();
        $suppliers = MsSuppliers::find($purchase->supplier_id);
        $poSign = PrmConfig::find(2);

        return view('livewire.purchase.purchase-non-view-print-manager', ['purchase' => $purchase, 'items' => $purchaseDetails, 'companies' => $company, 'suppliers' => $suppliers, 'poSign' => $poSign]);
    }
}
