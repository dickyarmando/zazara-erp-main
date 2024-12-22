<?php

namespace App\Http\Livewire\Reports;

use App\Models\TrSales;
use App\Models\TrSalesNon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class SalesReportManager extends Component
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

        // Query summary
        $salesTaxSummary = TrSales::leftJoin('ms_customers', 'ms_customers.id', '=', 'tr_sales.customer_id')
            ->selectRaw('SUM(total) as total_payment, SUM(payment) as paid, SUM(rest) as unpaid');
        $salesNonTaxSummary = TrSalesNon::leftJoin('ms_customers', 'ms_customers.id', '=', 'tr_sales_non.customer_id')
            ->selectRaw('SUM(total) as total_payment, SUM(payment) as paid, SUM(rest) as unpaid');

        if (!empty($this->searchKeyword)) {
            $salesTax->orWhere('number', 'like', "%" . $this->searchKeyword . "%");
            $salesTax->orWhere('ms_customers.company_name', 'like', "%" . $this->searchKeyword . "%");
            $salesTax->orWhere('total', 'like', "%" . $this->searchKeyword . "%");
            $salesTax->orWhere('payment', 'like', "%" . $this->searchKeyword . "%");
            $salesTax->orWhere('rest', 'like', "%" . $this->searchKeyword . "%");

            $salesNonTax->orWhere('number', 'like', "%" . $this->searchKeyword . "%");
            $salesNonTax->orWhere('ms_customers.company_name', 'like', "%" . $this->searchKeyword . "%");
            $salesNonTax->orWhere('total', 'like', "%" . $this->searchKeyword . "%");
            $salesNonTax->orWhere('payment', 'like', "%" . $this->searchKeyword . "%");
            $salesNonTax->orWhere('rest', 'like', "%" . $this->searchKeyword . "%");

            $salesTaxSummary->orWhere('tr_sales.number', 'like', "%" . $this->searchKeyword . "%");
            $salesTaxSummary->orWhere('ms_customers.company_name', 'like', "%" . $this->searchKeyword . "%");
            $salesTaxSummary->orWhere('tr_sales.total', 'like', "%" . $this->searchKeyword . "%");
            $salesTaxSummary->orWhere('tr_sales.payment', 'like', "%" . $this->searchKeyword . "%");
            $salesTaxSummary->orWhere('tr_sales.rest', 'like', "%" . $this->searchKeyword . "%");

            $salesNonTaxSummary->orWhere('tr_sales_non.number', 'like', "%" . $this->searchKeyword . "%");
            $salesNonTaxSummary->orWhere('ms_customers.company_name', 'like', "%" . $this->searchKeyword . "%");
            $salesNonTaxSummary->orWhere('tr_sales_non.total', 'like', "%" . $this->searchKeyword . "%");
            $salesNonTaxSummary->orWhere('tr_sales_non.payment', 'like', "%" . $this->searchKeyword . "%");
            $salesNonTaxSummary->orWhere('tr_sales_non.rest', 'like', "%" . $this->searchKeyword . "%");
        }

        $salesSummary = DB::query()
            ->selectRaw('SUM(total_payment) as total_payment, SUM(paid) as paid, SUM(unpaid) as unpaid')
            ->fromSub($salesTaxSummary->unionAll($salesNonTaxSummary), 'combined_summary')
            ->first();

        $unionSales = $salesTax->union($salesNonTax)->orderBy($this->sortColumn, $this->sortOrder);
        $saless = $unionSales->paginate($this->perPage);

        return view('livewire.reports.sales-report-manager', compact('saless', 'salesSummary'));
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
}
