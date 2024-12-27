<?php

namespace App\Http\Livewire\Invoice;

use App\Models\MsCustomers;
use App\Models\PrmCompanies;
use App\Models\PrmConfig;
use App\Models\TrInvoicesNon;
use App\Models\TrSalesNon;
use App\Models\TrSalesNonDetails;
use Livewire\Component;

class InvoiceNonViewManager extends Component
{
    public $set_id;
    public $set_inv_id;

    public $invoices = [];

    public function mount()
    {
        $this->set_inv_id = request()->id;
        $invoices = TrInvoicesNon::find($this->set_inv_id);
        $this->set_id = $invoices->sales_non_id;
        $this->invoices = $invoices;
    }
    public function render()
    {
        $companies = PrmCompanies::find(1);
        $sales = TrSalesNon::find($this->set_id);
        $items = TrSalesNonDetails::where('sales_non_id', $sales->id)
            ->select('id', 'product_name as name', 'unit_name as unit', 'qty', 'rate as price', 'amount as total')
            ->get()->toArray();
        $customers = MsCustomers::find($sales->customer_id);
        $invSignName = PrmConfig::find(5);
        $invSignPosition = PrmConfig::find(6);
        $invTC = PrmConfig::find(7);

        return view('livewire.invoice.invoice-non-view-manager', compact('companies', 'sales', 'items', 'customers', 'invSignName', 'invSignPosition', 'invTC'));
    }

    public function backRedirect()
    {
        return redirect()->to('/sales/non-tax');
    }

    public function printDocument()
    {
        $this->dispatchBrowserEvent('print');
    }
}
