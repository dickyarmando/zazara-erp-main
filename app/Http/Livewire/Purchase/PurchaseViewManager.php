<?php

namespace App\Http\Livewire\Purchase;

use App\Models\MsSuppliers;
use App\Models\PrmCompanies;
use App\Models\PrmConfig;
use App\Models\TrPurchase;
use App\Models\TrPurchaseDetails;
use Livewire\Component;

class PurchaseViewManager extends Component
{
    public $set_id;

    public function mount()
    {
        $this->set_id = request()->id;
    }

    public function render()
    {
        $company = PrmCompanies::find(1);
        $purchase = TrPurchase::find($this->set_id);
        $purchaseDetails = TrPurchaseDetails::where('purchase_id', $purchase->id)
            ->select('id', 'product_name as name', 'unit_name as unit', 'qty', 'rate as price', 'amount as total')
            ->get()->toArray();
        $suppliers = MsSuppliers::find($purchase->supplier_id);
        $poSign = PrmConfig::find(2);

        return view('livewire.purchase.purchase-view-manager', ['purchase' => $purchase, 'items' => $purchaseDetails, 'companies' => $company, 'suppliers' => $suppliers, 'poSign' => $poSign]);
    }

    public function backRedirect()
    {
        return redirect()->to('/purchase');
    }

    public function printDocument()
    {
        $this->dispatchBrowserEvent('print');
    }
}
