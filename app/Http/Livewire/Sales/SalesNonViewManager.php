<?php

namespace App\Http\Livewire\Sales;

use App\Models\MsCustomers;
use App\Models\PrmCompanies;
use App\Models\PrmConfig;
use App\Models\PrmRoleMenus;
use App\Models\TrSalesNon;
use App\Models\TrSalesNonDetails;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class SalesNonViewManager extends Component
{
    public $set_id;
    public $user_role;
    public $userRoles = [];

    public function mount()
    {
        $this->set_id = request()->id;
        $this->user_role = Auth::user()->role_id;
        $this->userRoles = PrmRoleMenus::where('menu_id', '31')->where('role_id', $this->user_role)->first();
    }

    public function render()
    {
        $companies = PrmCompanies::find(1);
        $sales = TrSalesNon::find($this->set_id);
        $items = TrSalesNonDetails::where('sales_non_id', $sales->id)
            ->select('id', 'product_code as code', 'product_name as name', 'unit_name as unit', 'qty', 'rate as price', 'amount as total')
            ->get()->toArray();
        $customers = MsCustomers::find($sales->customer_id);
        $soSign = PrmConfig::find(3);
        $soTC = PrmConfig::find(4);
        $soSignImg = PrmConfig::find(15);

        return view('livewire.sales.sales-non-view-manager', compact('companies', 'sales', 'items', 'customers', 'soSign', 'soTC', 'soSignImg'));
    }

    public function backRedirect()
    {
        return redirect()->to('/sales/non-tax');
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

        $tp = TrSalesNon::find($this->set_id);
        $tp->update($valid);

        session()->flash('success', 'Approved');
        $this->dispatchBrowserEvent('close-modal');
    }

    public function cancelSales()
    {
        $now = Carbon::now();
        $valid = [
            'is_status' => '0',
            'updated_at' => $now->toDateTimeString(),
            'updated_by' => Auth::user()->id
        ];

        $tp = TrSalesNon::find($this->set_id);
        $tp->update($valid);

        session()->flash('success', 'Cancelled');
        $this->dispatchBrowserEvent('close-modal');
    }

    public function unapproved()
    {
        $now = Carbon::now();
        $valid = [
            'approved_at' => null,
            'approved_by' => null,
            'updated_at' => $now->toDateTimeString(),
            'updated_by' => Auth::user()->id
        ];

        $tp = TrSalesNon::find($this->set_id);
        $tp->update($valid);

        session()->flash('success', 'unapproved');
        $this->dispatchBrowserEvent('close-modal');
    }
}
