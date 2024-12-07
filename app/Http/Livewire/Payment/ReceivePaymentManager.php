<?php

namespace App\Http\Livewire\Payment;

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
        $salesTax = TrSales::leftJoin('ms_customers', 'ms_customers.id', '=', 'tr_sales.customer_id')
            ->select('tr_sales.id', 'tr_sales.number', 'tr_sales.date', 'tr_sales.customer_id', 'ms_customers.company_name as customer_name', 'tr_sales.reference', 'tr_sales.total', 'tr_sales.payment', 'tr_sales.rest', 'tr_sales.notes', 'tr_sales.is_receive', 'tr_sales.is_status')
            ->addSelect(DB::raw('"Tax" as type'));

        $salesNonTax = TrSalesNon::leftJoin('ms_customers', 'ms_customers.id', '=', 'tr_sales_non.customer_id')
            ->select('tr_sales_non.id', 'tr_sales_non.number', 'tr_sales_non.date', 'tr_sales_non.customer_id', 'ms_customers.company_name as customer_name', 'tr_sales_non.reference', 'tr_sales_non.total', 'tr_sales_non.payment', 'tr_sales_non.rest', 'tr_sales_non.notes', 'tr_sales_non.is_receive', 'tr_sales_non.is_status')
            ->addSelect(DB::raw('"Non" as type'));

        $saless = $salesTax->union($salesNonTax)->orderBy($this->sortColumn, $this->sortOrder);

        if (!empty($this->searchKeyword)) {
            $saless->orWhere('number', 'like', "%" . $this->searchKeyword . "%");
            $saless->orWhere('customer_name', 'like', "%" . $this->searchKeyword . "%");
            $saless->orWhere('total', 'like', "%" . $this->searchKeyword . "%");
            $saless->orWhere('payment', 'like', "%" . $this->searchKeyword . "%");
            $saless->orWhere('rest', 'like', "%" . $this->searchKeyword . "%");
        }

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
