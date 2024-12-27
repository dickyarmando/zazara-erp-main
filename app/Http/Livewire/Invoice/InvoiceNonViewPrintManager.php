<?php

namespace App\Http\Livewire\Invoice;

use App\Http\Controllers\Controller;
use App\Models\MsCustomers;
use App\Models\PrmCompanies;
use App\Models\PrmConfig;
use App\Models\TrInvoicesNon;
use App\Models\TrSalesNon;
use App\Models\TrSalesNonDetails;

class InvoiceNonViewPrintManager extends Controller
{
    public function index($id)
    {
        $companies = PrmCompanies::find(1);
        $invoices = TrInvoicesNon::find($id);
        $sales = TrSalesNon::find($invoices->sales_non_id);
        $items = TrSalesNonDetails::where('sales_non_id', $sales->id)
            ->select('id', 'product_name as name', 'unit_name as unit', 'qty', 'rate as price', 'amount as total')
            ->get()->toArray();
        $customers = MsCustomers::find($sales->customer_id);
        $invSignName = PrmConfig::find(5);
        $invSignPosition = PrmConfig::find(6);
        $invTC = PrmConfig::find(7);

        return view('livewire.invoice.invoice-non-view-print-manager', compact('companies', 'invoices', 'sales', 'items', 'customers', 'invSignName', 'invSignPosition', 'invTC'));
    }
}
