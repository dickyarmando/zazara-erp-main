<?php

namespace App\Http\Livewire\Reports;

use App\Models\TrPurchase;
use App\Models\TrPurchaseNon;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class PurchaseReportManager extends Component
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
    public $supplier;
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
        $purchases = $this->dataPurchase()->paginate($this->perPage);

        return view('livewire.reports.purchase-report-manager', compact('purchases'));
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

    public function dataPurchase()
    {
        $purchaseTax = TrPurchase::leftJoin('ms_suppliers', 'ms_suppliers.id', '=', 'tr_purchase.supplier_id')
            ->join('tr_purchase_details', 'tr_purchase.id', '=', 'tr_purchase_details.purchase_id')
            ->select('tr_purchase.id', 'tr_purchase.number', 'tr_purchase.date', 'tr_purchase.supplier_id', 'ms_suppliers.company_name as supplier_name', 'tr_purchase.payment', 'tr_purchase.approved_at', 'tr_purchase.approved_by', 'tr_purchase.is_payed', 'tr_purchase.is_status', 'tr_purchase_details.product_name', 'tr_purchase_details.unit_name', 'tr_purchase_details.qty', 'tr_purchase_details.rate', 'tr_purchase_details.amount')
            ->addSelect(DB::raw('"Tax" as type'))
            ->whereBetween('tr_purchase.date', [$this->start_date, $this->end_date]);

        $purchaseNonTax = TrPurchaseNon::leftJoin('ms_suppliers', 'ms_suppliers.id', '=', 'tr_purchase_non.supplier_id')
            ->join('tr_purchase_non_details', 'tr_purchase_non.id', '=', 'tr_purchase_non_details.purchase_non_id')
            ->select('tr_purchase_non.id', 'tr_purchase_non.number', 'tr_purchase_non.date', 'tr_purchase_non.supplier_id', 'ms_suppliers.company_name as supplier_name', 'tr_purchase_non.payment', 'tr_purchase_non.approved_at', 'tr_purchase_non.approved_by', 'tr_purchase_non.is_payed', 'tr_purchase_non.is_status', 'tr_purchase_non_details.product_name', 'tr_purchase_non_details.unit_name', 'tr_purchase_non_details.qty', 'tr_purchase_non_details.rate', 'tr_purchase_non_details.amount')
            ->addSelect(DB::raw('"Non" as type'))
            ->whereBetween('tr_purchase_non.date', [$this->start_date, $this->end_date]);

        if (!empty($this->number)) {
            $purchaseTax->where('tr_purchase.number', 'like', "%" . $this->number . "%");

            $purchaseNonTax->where('tr_purchase_non.number', 'like', "%" . $this->number . "%");
        }

        if (!empty($this->customer)) {
            $purchaseTax->where('ms_suppliers.company_name', 'like', "%" . $this->customer . "%");

            $purchaseNonTax->where('ms_suppliers.company_name', 'like', "%" . $this->customer . "%");
        }

        if (!empty($this->product)) {
            $purchaseTax->where('tr_purchase_details.product_name', 'like', "%" . $this->product . "%");

            $purchaseNonTax->where('tr_purchase_non_details.product_name', 'like', "%" . $this->product . "%");
        }

        $unionPurchase = $purchaseTax->union($purchaseNonTax)->orderBy($this->sortColumn, $this->sortOrder);

        return $unionPurchase;
    }

    public function printTable()
    {
        $responseData = [
            'sd' => $this->start_date,
            'ed' => $this->end_date,
            'n' => $this->number,
            's' => $this->supplier,
            'p' => $this->product,
        ];

        $url = route('print.purchase', $responseData);
        $this->dispatchBrowserEvent('openTab', ['url' => $url]);
    }
}
