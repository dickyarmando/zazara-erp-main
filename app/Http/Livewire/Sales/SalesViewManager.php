<?php

namespace App\Http\Livewire\Sales;

use App\Models\MsCustomers;
use App\Models\PrmCompanies;
use App\Models\PrmConfig;
use App\Models\PrmRoleMenus;
use App\Models\TrSales;
use App\Models\TrSalesDetails;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class SalesViewManager extends Component
{
    public $set_id;
    public $userRoles = [];

    public function mount()
    {
        $this->set_id = request()->id;
        $this->userRoles = PrmRoleMenus::where('menu_id', '30')->where('role_id', Auth::user()->role_id)->first();
    }

    public function render()
    {
        $companies = PrmCompanies::find(1);
        $sales = TrSales::find($this->set_id);
        $items = TrSalesDetails::where('sales_id', $sales->id)
            ->select('id', 'product_name as name', 'unit_name as unit', 'qty', 'rate as price', 'amount as total')
            ->get()->toArray();
        $customers = MsCustomers::find($sales->customer_id);
        $soSign = PrmConfig::find(3);
        $soTC = PrmConfig::find(4);

        return view('livewire.sales.sales-view-manager', compact('companies', 'sales', 'items', 'customers', 'soSign', 'soTC'));
    }

    public function backRedirect()
    {
        return redirect()->to('/sales');
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

        $tp = TrSales::find($this->set_id);
        $tp->update($valid);

        session()->flash('success', 'Approved');
        $this->dispatchBrowserEvent('close-modal');
    }
}
