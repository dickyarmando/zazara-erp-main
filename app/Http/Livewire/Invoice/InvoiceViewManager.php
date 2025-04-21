<?php

namespace App\Http\Livewire\Invoice;

use App\Models\MsCustomers;
use App\Models\PrmCompanies;
use App\Models\PrmConfig;
use App\Models\PrmRoleMenus;
use App\Models\TrInvoice;
use App\Models\TrSales;
use App\Models\TrSalesDetails;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class InvoiceViewManager extends Component
{
    public $set_id;
    public $set_inv_id;

    public $invoices = [];
    public $userRoles = [];

    public function mount()
    {
        $this->set_inv_id = request()->id;
        $invoices = TrInvoice::find($this->set_inv_id);
        $this->set_id = $invoices->sales_id;
        $this->invoices = $invoices;

        $this->userRoles = PrmRoleMenus::where('menu_id', '11')->where('role_id', Auth::user()->role_id)->first();
    }

    public function render()
    {
        $companies = PrmCompanies::find(1);
        $sales = TrSales::find($this->set_id);
        $items = TrSalesDetails::where('sales_id', $sales->id)
            ->select('id', 'product_name as name', 'unit_name as unit', 'qty', 'rate as price', 'amount as total')
            ->get()->toArray();
        $customers = MsCustomers::find($sales->customer_id);
        $invSignName = PrmConfig::find(5);
        $invSignPosition = PrmConfig::find(6);
        $invTC = PrmConfig::find(7);
        $invSignImg = PrmConfig::find(19);

        return view('livewire.invoice.invoice-view-manager', compact('companies', 'sales', 'items', 'customers', 'invSignName', 'invSignPosition', 'invTC', 'invSignImg'));
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

        $tp = TrInvoice::find($this->set_inv_id);
        $tp->update($valid);

        session()->flash('success', 'Approved');
        $this->dispatchBrowserEvent('close-modal');
    }
}
