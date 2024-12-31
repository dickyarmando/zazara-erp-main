<?php

namespace App\Http\Livewire\Payment;

use App\Models\TrInvoice;
use App\Models\TrInvoicesNon;
use App\Models\TrSales;
use App\Models\TrSalesNon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class ReceivePaymentManager extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $perPage = 10;
    public $sortColumn = "date";
    public $sortOrder = "desc";
    public $sortLink = '<i class="sorticon fa-solid fa-caret-up"></i>';
    public $searchKeyword = '';
    public $roleFilter = '';
    public $set_id;

    public function render()
    {
        $salesTax = TrInvoice::leftJoin('tr_sales', 'tr_invoices.sales_id', '=', 'tr_sales.id')
            ->leftJoin('ms_customers', 'ms_customers.id', '=', 'tr_sales.customer_id')
            ->select('tr_sales.id', 'tr_sales.number', 'tr_invoices.id as invoice_id', 'tr_invoices.number as invoice_number', 'tr_invoices.date as invoice_date', 'tr_invoices.due_termin', 'tr_invoices.due_date', 'tr_sales.date', 'tr_sales.customer_id', 'ms_customers.company_name as customer_name', 'tr_sales.reference', 'tr_invoices.total', 'tr_invoices.payment', 'tr_invoices.rest', 'tr_invoices.notes', 'tr_invoices.is_receive', 'tr_invoices.is_status')
            ->addSelect(DB::raw('"Tax" as type'))
            ->where('tr_invoices.is_receive', '0')
            ->where('tr_invoices.approved_at', '!=', null);

        $salesNonTax = TrInvoicesNon::leftJoin('tr_sales_non', 'tr_invoices_nons.sales_non_id', '=', 'tr_sales_non.id')
            ->leftJoin('ms_customers', 'ms_customers.id', '=', 'tr_sales_non.customer_id')
            ->select('tr_sales_non.id', 'tr_sales_non.number', 'tr_invoices_nons.id as invoice_id', 'tr_invoices_nons.number as invoice_number', 'tr_invoices_nons.date as invoice_date', 'tr_invoices_nons.due_termin', 'tr_invoices_nons.due_date', 'tr_sales_non.date', 'tr_sales_non.customer_id', 'ms_customers.company_name as customer_name', 'tr_sales_non.reference', 'tr_invoices_nons.total', 'tr_invoices_nons.payment', 'tr_invoices_nons.rest', 'tr_invoices_nons.notes', 'tr_invoices_nons.is_receive', 'tr_invoices_nons.is_status')
            ->addSelect(DB::raw('"Non" as type'))
            ->where('tr_invoices_nons.is_receive', '0')
            ->where('tr_invoices_nons.approved_at', '!=', null);

        if (!empty($this->searchKeyword)) {
            $salesTax->orWhere('number', 'like', "%" . $this->searchKeyword . "%")->where('tr_invoices.is_receive', '0')->where('tr_invoices.approved_at', '!=', null);
            $salesTax->orWhere('invoice_number', 'like', "%" . $this->searchKeyword . "%")->where('tr_invoices.is_receive', '0')->where('tr_invoices.approved_at', '!=', null);
            $salesTax->orWhere('ms_customers.company_name', 'like', "%" . $this->searchKeyword . "%")->where('tr_invoices.is_receive', '0')->where('tr_invoices.approved_at', '!=', null);
            $salesTax->orWhere('total', 'like', "%" . $this->searchKeyword . "%")->where('tr_invoices.is_receive', '0')->where('tr_invoices.approved_at', '!=', null);
            $salesTax->orWhere('payment', 'like', "%" . $this->searchKeyword . "%")->where('tr_invoices.is_receive', '0')->where('tr_invoices.approved_at', '!=', null);
            $salesTax->orWhere('rest', 'like', "%" . $this->searchKeyword . "%")->where('tr_invoices.is_receive', '0')->where('tr_invoices.approved_at', '!=', null);

            $salesNonTax->orWhere('number', 'like', "%" . $this->searchKeyword . "%")->where('tr_invoices_nons.is_receive', '0')->where('tr_invoices_nons.approved_at', '!=', null);
            $salesNonTax->orWhere('invoice_number', 'like', "%" . $this->searchKeyword . "%")->where('tr_invoices_nons.is_receive', '0')->where('tr_invoices_nons.approved_at', '!=', null);
            $salesNonTax->orWhere('ms_customers.company_name', 'like', "%" . $this->searchKeyword . "%")->where('tr_invoices_nons.is_receive', '0')->where('tr_invoices_nons.approved_at', '!=', null);
            $salesNonTax->orWhere('total', 'like', "%" . $this->searchKeyword . "%")->where('tr_invoices_nons.is_receive', '0')->where('tr_invoices_nons.approved_at', '!=', null);
            $salesNonTax->orWhere('payment', 'like', "%" . $this->searchKeyword . "%")->where('tr_invoices_nons.is_receive', '0')->where('tr_invoices_nons.approved_at', '!=', null);
            $salesNonTax->orWhere('rest', 'like', "%" . $this->searchKeyword . "%")->where('tr_invoices_nons.is_receive', '0')->where('tr_invoices_nons.approved_at', '!=', null);
        }

        $saless = $salesTax->union($salesNonTax)->orderBy($this->sortColumn, $this->sortOrder);
        $saless = $saless->paginate($this->perPage);

        return view('livewire.payment.receive-payment-manager', compact('saless'));
    }

    public function sortOrder($columnName = "")
    {
        $caretOrder = "up";
        if ($this->sortOrder == 'asc') {
            $this->sortOrder = 'desc';
            $caretOrder = "down";
        } else {
            $this->sortOrder = 'asc';
            $caretOrder = "up";
        }
        $this->sortLink = '<i class="sorticon fa-solid fa-caret-' . $caretOrder . '"></i>';
        $this->sortColumn = $columnName;
    }

    public function view($id, $type)
    {
        return redirect()->to('/receive/view/' . $id . '/' . $type);
    }
}
