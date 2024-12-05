<?php

namespace App\Http\Livewire\Sales;

use App\Models\MsCustomers;
use App\Models\PrmCompanies;
use App\Models\TrSales;
use App\Models\TrSalesDetails;
use Livewire\Component;

class SalesViewManager extends Component
{
    public $set_id;

    public function mount()
    {
        $this->set_id = request()->id;
    }

    public function render()
    {
        $companies = PrmCompanies::find(1);
        $sales = TrSales::find($this->set_id);
        $items = TrSalesDetails::where('sales_id', $sales->id)
            ->select('id', 'product_name as name', 'unit_name as unit', 'qty', 'rate as price', 'amount as total')
            ->get()->toArray();
        $customers = MsCustomers::find($sales->customer_id);

        return view('livewire.sales.sales-view-manager', compact('companies', 'sales', 'items', 'customers'));
    }

    public function backRedirect()
    {
        return redirect()->to('/sales');
    }

    public function printDocument()
    {
        $this->dispatchBrowserEvent('print');
    }
}
