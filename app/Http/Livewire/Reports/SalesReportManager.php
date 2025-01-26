<?php

namespace App\Http\Livewire\Reports;

use App\Models\TrSales;
use App\Models\TrSalesNon;
use Carbon\Carbon;
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

    public $start_date;
    public $end_date;
    public $number;
    public $customer;
    public $product;
    public $approved;
    public $payment;

    public function mount()
    {
        $this->start_date = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->end_date = Carbon::now()->format('Y-m-d');
    }

    public function render()
    {
        $saless = $this->dataSales()->paginate($this->perPage);

        return view('livewire.reports.sales-report-manager', compact('saless'));
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

    public function dataSales()
    {
        $salesTax = TrSales::leftJoin('ms_customers', 'ms_customers.id', '=', 'tr_sales.customer_id')
            ->join('tr_sales_details', 'tr_sales.id', '=', 'tr_sales_details.sales_id')
            ->select('tr_sales.id', 'tr_sales.number', 'tr_sales.date', 'ms_customers.company_name as customer_name', 'tr_sales.approved_at', 'tr_sales.approved_by', 'tr_sales.is_invoice', 'tr_sales.payment', 'tr_sales.is_receive', 'tr_sales.is_status', 'tr_sales_details.product_code', 'tr_sales_details.product_name', 'tr_sales_details.unit_name', 'tr_sales_details.qty', 'tr_sales_details.rate', 'tr_sales_details.amount')
            ->addSelect(DB::raw('"Tax" as type'))
            ->whereBetween('tr_sales.date', [$this->start_date, $this->end_date]);

        $salesNonTax = TrSalesNon::leftJoin('ms_customers', 'ms_customers.id', '=', 'tr_sales_non.customer_id')
            ->join('tr_sales_non_details', 'tr_sales_non.id', '=', 'tr_sales_non_details.sales_non_id')
            ->select('tr_sales_non.id', 'tr_sales_non.number', 'tr_sales_non.date', 'ms_customers.company_name as customer_name', 'tr_sales_non.approved_at', 'tr_sales_non.approved_by', 'tr_sales_non.is_invoice', 'tr_sales_non.payment', 'tr_sales_non.is_receive', 'tr_sales_non.is_status', 'tr_sales_non_details.product_code', 'tr_sales_non_details.product_name', 'tr_sales_non_details.unit_name', 'tr_sales_non_details.qty', 'tr_sales_non_details.rate', 'tr_sales_non_details.amount')
            ->addSelect(DB::raw('"Non" as type'))
            ->whereBetween('tr_sales_non.date', [$this->start_date, $this->end_date]);

        if (!empty($this->number)) {
            $salesTax->where('tr_sales.number', 'like', "%" . $this->number . "%");

            $salesNonTax->where('tr_sales_non.number', 'like', "%" . $this->number . "%");
        }

        if (!empty($this->customer)) {
            $salesTax->where('ms_customers.company_name', 'like', "%" . $this->customer . "%");

            $salesNonTax->where('ms_customers.company_name', 'like', "%" . $this->customer . "%");
        }

        if (!empty($this->product)) {
            $salesTax->where('tr_sales_details.product_name', 'like', "%" . $this->product . "%");

            $salesNonTax->where('tr_sales_non_details.product_name', 'like', "%" . $this->product . "%");
        }

        $unionSales = $salesTax->union($salesNonTax)->orderBy($this->sortColumn, $this->sortOrder);

        return $unionSales;
    }

    public function printTable()
    {
        $responseData = [
            'sd' => $this->start_date,
            'ed' => $this->end_date,
            'n' => $this->number,
            'c' => $this->customer,
            'p' => $this->product,
        ];

        $url = route('print.sales', $responseData);
        $this->dispatchBrowserEvent('openTab', ['url' => $url]);
    }
}
