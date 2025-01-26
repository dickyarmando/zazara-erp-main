<?php

namespace App\Http\Livewire\Purchase;

use App\Models\MsSuppliers;
use App\Models\PrmCompanies;
use App\Models\PrmConfig;
use App\Models\PrmRoleMenus;
use App\Models\TrPurchase;
use App\Models\TrPurchaseDetails;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class PurchaseViewManager extends Component
{
    public $set_id;
    public $userRoles = [];

    public function mount()
    {
        $this->set_id = request()->id;
        $this->userRoles = PrmRoleMenus::where('menu_id', '28')->where('role_id', Auth::user()->role_id)->first();
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

    public function approve()
    {
        $now = Carbon::now();
        $valid = [
            'approved_at' => $now->toDateTimeString(),
            'approved_by' => Auth::user()->id,
            'updated_at' => $now->toDateTimeString(),
            'updated_by' => Auth::user()->id
        ];

        $tp = TrPurchase::find($this->set_id);
        $tp->update($valid);

        session()->flash('success', 'Approved');
        $this->dispatchBrowserEvent('close-modal');
    }

    public function destroy()
    {
        $valid = [
            'is_status' => '0',
            'deleted_at' => Carbon::now()->toDateTimeString(),
            'deleted_by' => Auth::user()->id
        ];

        $tp = TrPurchase::find($this->set_id);
        $tp->update($valid);

        session()->flash('success', 'Canceled Purchase');
        $this->dispatchBrowserEvent('close-modal');
    }
}
