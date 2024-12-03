<?php

namespace App\Http\Livewire\Purchase;

use App\Models\MsSuppliers;
use App\Models\PrmCompanies;
use App\Models\TrPurchaseNon;
use App\Models\TrPurchaseNonDetails;
use Livewire\Component;

class PurchaseNonViewManager extends Component
{
    public $set_id;

    public function mount()
    {
        $this->set_id = request()->id;
    }

    public function render()
    {
        $company = PrmCompanies::find(1);
        $purchase = TrPurchaseNon::find($this->set_id);
        $purchaseDetails = TrPurchaseNonDetails::where('purchase_non_id', $purchase->id)
            ->select('id', 'product_name as name', 'unit_name as unit', 'qty', 'rate as price', 'amount as total')
            ->get()->toArray();
        $suppliers = MsSuppliers::find($purchase->supplier_id);

        return view('livewire.purchase.purchase-non-view-manager', ['purchase' => $purchase, 'items' => $purchaseDetails, 'companies' => $company, 'suppliers' => $suppliers]);
    }

    public function backRedirect()
    {
        return redirect()->to('/purchase/non-tax');
    }

    public function printDocument()
    {
        $this->dispatchBrowserEvent('print');
    }
}
