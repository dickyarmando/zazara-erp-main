<?php

namespace App\Http\Livewire\Reports;

use App\Models\TrSales;
use App\Models\TrSalesNon;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class ReceiveReportManager extends Component
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

    public $start_date;
    public $end_date;

    public function mount()
    {
        $this->start_date = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->end_date = Carbon::now()->format('Y-m-d');
    }

    public function render()
    {
        $salesTax = TrSales::leftJoin('ms_customers', 'ms_customers.id', '=', 'tr_sales.customer_id')
            ->join('tr_invoices', 'tr_invoices.sales_id', '=', 'tr_sales.id')
            ->select('tr_sales.id', 'tr_sales.number', 'tr_invoices.number as invoice_number', 'tr_invoices.date', 'tr_sales.customer_id', 'ms_customers.company_name as customer_name', 'tr_sales.reference', 'tr_sales.total', 'tr_sales.payment', 'tr_sales.rest', 'tr_sales.notes', 'tr_sales.is_receive', 'tr_sales.is_status')
            ->addSelect(DB::raw('"Tax" as type'))
            ->whereBetween('tr_invoices.date', [$this->start_date, $this->end_date]);

        $salesNonTax = TrSalesNon::leftJoin('ms_customers', 'ms_customers.id', '=', 'tr_sales_non.customer_id')
            ->join('tr_invoices_nons', 'tr_invoices_nons.sales_non_id', '=', 'tr_sales_non.id')
            ->select('tr_sales_non.id', 'tr_sales_non.number', 'tr_invoices_nons.number as invoice_number', 'tr_invoices_nons.date', 'tr_sales_non.customer_id', 'ms_customers.company_name as customer_name', 'tr_sales_non.reference', 'tr_sales_non.total', 'tr_sales_non.payment', 'tr_sales_non.rest', 'tr_sales_non.notes', 'tr_sales_non.is_receive', 'tr_sales_non.is_status')
            ->addSelect(DB::raw('"Non" as type'))
            ->whereBetween('tr_invoices_nons.date', [$this->start_date, $this->end_date]);

        // Query summary
        $salesTaxSummary = TrSales::leftJoin('ms_customers', 'ms_customers.id', '=', 'tr_sales.customer_id')
            ->join('tr_invoices', 'tr_invoices.sales_id', '=', 'tr_sales.id')
            ->selectRaw('SUM(tr_sales.total) as total_payment, SUM(tr_sales.payment) as paid, SUM(tr_sales.rest) as unpaid')
            ->whereBetween('tr_invoices.date', [$this->start_date, $this->end_date]);
        $salesNonTaxSummary = TrSalesNon::leftJoin('ms_customers', 'ms_customers.id', '=', 'tr_sales_non.customer_id')
            ->join('tr_invoices_nons', 'tr_invoices_nons.sales_non_id', '=', 'tr_sales_non.id')
            ->selectRaw('SUM(tr_sales_non.total) as total_payment, SUM(tr_sales_non.payment) as paid, SUM(tr_sales_non.rest) as unpaid')
            ->whereBetween('tr_invoices_nons.date', [$this->start_date, $this->end_date]);

        if (!empty($this->searchKeyword)) {
            $salesTax->orWhere('number', 'like', "%" . $this->searchKeyword . "%")->whereBetween('tr_invoices.date', [$this->start_date, $this->end_date]);
            $salesTax->orWhere('invoice_number', 'like', "%" . $this->searchKeyword . "%")->whereBetween('tr_invoices.date', [$this->start_date, $this->end_date]);
            $salesTax->orWhere('ms_customers.company_name', 'like', "%" . $this->searchKeyword . "%")->whereBetween('tr_invoices.date', [$this->start_date, $this->end_date]);
            $salesTax->orWhere('total', 'like', "%" . $this->searchKeyword . "%")->whereBetween('tr_invoices.date', [$this->start_date, $this->end_date]);
            $salesTax->orWhere('payment', 'like', "%" . $this->searchKeyword . "%")->whereBetween('tr_invoices.date', [$this->start_date, $this->end_date]);
            $salesTax->orWhere('rest', 'like', "%" . $this->searchKeyword . "%")->whereBetween('tr_invoices.date', [$this->start_date, $this->end_date]);

            $salesNonTax->orWhere('number', 'like', "%" . $this->searchKeyword . "%")->whereBetween('tr_invoices_nons.date', [$this->start_date, $this->end_date]);
            $salesNonTax->orWhere('invoice_number', 'like', "%" . $this->searchKeyword . "%")->whereBetween('tr_invoices_nons.date', [$this->start_date, $this->end_date]);
            $salesNonTax->orWhere('ms_customers.company_name', 'like', "%" . $this->searchKeyword . "%")->whereBetween('tr_invoices_nons.date', [$this->start_date, $this->end_date]);
            $salesNonTax->orWhere('total', 'like', "%" . $this->searchKeyword . "%")->whereBetween('tr_invoices_nons.date', [$this->start_date, $this->end_date]);
            $salesNonTax->orWhere('payment', 'like', "%" . $this->searchKeyword . "%")->whereBetween('tr_invoices_nons.date', [$this->start_date, $this->end_date]);
            $salesNonTax->orWhere('rest', 'like', "%" . $this->searchKeyword . "%")->whereBetween('tr_invoices_nons.date', [$this->start_date, $this->end_date]);

            $salesTaxSummary->orWhere('tr_sales.number', 'like', "%" . $this->searchKeyword . "%")->whereBetween('tr_invoices.date', [$this->start_date, $this->end_date]);
            $salesTaxSummary->orWhere('tr_invoices.number', 'like', "%" . $this->searchKeyword . "%")->whereBetween('tr_invoices.date', [$this->start_date, $this->end_date]);
            $salesTaxSummary->orWhere('ms_customers.company_name', 'like', "%" . $this->searchKeyword . "%")->whereBetween('tr_invoices.date', [$this->start_date, $this->end_date]);
            $salesTaxSummary->orWhere('tr_sales.total', 'like', "%" . $this->searchKeyword . "%")->whereBetween('tr_invoices.date', [$this->start_date, $this->end_date]);
            $salesTaxSummary->orWhere('tr_sales.payment', 'like', "%" . $this->searchKeyword . "%")->whereBetween('tr_invoices.date', [$this->start_date, $this->end_date]);
            $salesTaxSummary->orWhere('tr_sales.rest', 'like', "%" . $this->searchKeyword . "%")->whereBetween('tr_invoices.date', [$this->start_date, $this->end_date]);

            $salesNonTaxSummary->orWhere('tr_sales_non.number', 'like', "%" . $this->searchKeyword . "%")->whereBetween('tr_invoices_nons.date', [$this->start_date, $this->end_date]);
            $salesNonTaxSummary->orWhere('tr_invoices_nons.number', 'like', "%" . $this->searchKeyword . "%")->whereBetween('tr_invoices_nons.date', [$this->start_date, $this->end_date]);
            $salesNonTaxSummary->orWhere('ms_customers.company_name', 'like', "%" . $this->searchKeyword . "%")->whereBetween('tr_invoices_nons.date', [$this->start_date, $this->end_date]);
            $salesNonTaxSummary->orWhere('tr_sales_non.total', 'like', "%" . $this->searchKeyword . "%")->whereBetween('tr_invoices_nons.date', [$this->start_date, $this->end_date]);
            $salesNonTaxSummary->orWhere('tr_sales_non.payment', 'like', "%" . $this->searchKeyword . "%")->whereBetween('tr_invoices_nons.date', [$this->start_date, $this->end_date]);
            $salesNonTaxSummary->orWhere('tr_sales_non.rest', 'like', "%" . $this->searchKeyword . "%")->whereBetween('tr_invoices_nons.date', [$this->start_date, $this->end_date]);
        }

        $salesSummary = DB::query()
            ->selectRaw('SUM(total_payment) as total_payment, SUM(paid) as paid, SUM(unpaid) as unpaid')
            ->fromSub($salesTaxSummary->unionAll($salesNonTaxSummary), 'combined_summary')
            ->first();

        $unionSales = $salesTax->union($salesNonTax)->orderBy($this->sortColumn, $this->sortOrder);
        $saless = $unionSales->paginate($this->perPage);

        return view('livewire.reports.receive-report-manager', compact('saless', 'salesSummary'));
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

    public function printTable()
    {
        $responseData = [
            'sd' => $this->start_date,
            'ed' => $this->end_date,
            's' => $this->searchKeyword,
        ];

        $url = route('print.receive', $responseData);
        $this->dispatchBrowserEvent('openTab', ['url' => $url]);
    }
}
