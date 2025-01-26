<?php

namespace App\Http\Livewire\Sales;

use App\Http\Controllers\Controller;
use App\Models\MsCustomers;
use App\Models\PrmCompanies;
use App\Models\PrmConfig;
use App\Models\TrSalesNon;
use App\Models\TrSalesNonDetails;

class SalesNonViewPrintManager extends Controller
{
    public function index($id)
    {
        $companies = PrmCompanies::find(1);
        $sales = TrSalesNon::find($id);
        $items = TrSalesNonDetails::where('sales_non_id', $sales->id)
            ->select('id', 'product_code as code', 'product_name as name', 'unit_name as unit', 'qty', 'rate as price', 'amount as total')
            ->get()->toArray();
        $customers = MsCustomers::find($sales->customer_id);
        $soSign = PrmConfig::find(3);
        $soTC = PrmConfig::find(4);
        $soSignImg = PrmConfig::find(15);

        return view('livewire.sales.sales-non-view-print-manager', compact('companies', 'sales', 'items', 'customers', 'soSign', 'soTC', 'soSignImg'));
    }
}
