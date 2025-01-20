<?php

namespace App\Http\Livewire\Reports;

use App\Models\TrPurchase;
use App\Models\TrPurchaseNon;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class PayReportManager extends Component
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
        $purchaseTax = TrPurchase::leftJoin('ms_suppliers', 'ms_suppliers.id', '=', 'tr_purchase.supplier_id')
            ->select('tr_purchase.id', 'tr_purchase.number', 'tr_purchase.date', 'tr_purchase.supplier_id', 'ms_suppliers.company_name as supplier_name', 'tr_purchase.reference', 'tr_purchase.total', 'tr_purchase.payment', 'tr_purchase.rest', 'tr_purchase.notes', 'tr_purchase.is_payed', 'tr_purchase.is_status')
            ->addSelect(DB::raw('"Tax" as type'))
            ->whereNotNull('tr_purchase.approved_at')
            ->whereBetween('tr_purchase.date', [$this->start_date, $this->end_date]);

        $purchaseNonTax = TrPurchaseNon::leftJoin('ms_suppliers', 'ms_suppliers.id', '=', 'tr_purchase_non.supplier_id')
            ->select('tr_purchase_non.id', 'tr_purchase_non.number', 'tr_purchase_non.date', 'tr_purchase_non.supplier_id', 'ms_suppliers.company_name as supplier_name', 'tr_purchase_non.reference', 'tr_purchase_non.total', 'tr_purchase_non.payment', 'tr_purchase_non.rest', 'tr_purchase_non.notes', 'tr_purchase_non.is_payed', 'tr_purchase_non.is_status')
            ->addSelect(DB::raw('"Non" as type'))
            ->whereNotNull('tr_purchase_non.approved_at')
            ->whereBetween('tr_purchase_non.date', [$this->start_date, $this->end_date]);

        // Query summary
        $purchaseTaxSummary = TrPurchase::leftJoin('ms_suppliers', 'ms_suppliers.id', '=', 'tr_purchase.supplier_id')
            ->selectRaw('SUM(total) as total_payment, SUM(payment) as paid, SUM(rest) as unpaid')
            ->whereNotNull('tr_purchase.approved_at')
            ->whereBetween('tr_purchase.date', [$this->start_date, $this->end_date]);
        $purchaseNonTaxSummary = TrPurchaseNon::leftJoin('ms_suppliers', 'ms_suppliers.id', '=', 'tr_purchase_non.supplier_id')
            ->selectRaw('SUM(total) as total_payment, SUM(payment) as paid, SUM(rest) as unpaid')
            ->whereNotNull('tr_purchase_non.approved_at')
            ->whereBetween('tr_purchase_non.date', [$this->start_date, $this->end_date]);

        if (!empty($this->searchKeyword)) {
            $purchaseTax->orWhere('number', 'like', "%" . $this->searchKeyword . "%")->whereNotNull('tr_purchase.approved_at')->whereBetween('tr_purchase.date', [$this->start_date, $this->end_date]);
            $purchaseTax->orWhere('ms_suppliers.company_name', 'like', "%" . $this->searchKeyword . "%")->whereNotNull('tr_purchase.approved_at')->whereBetween('tr_purchase.date', [$this->start_date, $this->end_date]);
            $purchaseTax->orWhere('total', 'like', "%" . $this->searchKeyword . "%")->whereNotNull('tr_purchase.approved_at')->whereBetween('tr_purchase.date', [$this->start_date, $this->end_date]);
            $purchaseTax->orWhere('payment', 'like', "%" . $this->searchKeyword . "%")->whereNotNull('tr_purchase.approved_at')->whereBetween('tr_purchase.date', [$this->start_date, $this->end_date]);
            $purchaseTax->orWhere('rest', 'like', "%" . $this->searchKeyword . "%")->whereNotNull('tr_purchase.approved_at')->whereBetween('tr_purchase.date', [$this->start_date, $this->end_date]);

            $purchaseNonTax->orWhere('number', 'like', "%" . $this->searchKeyword . "%")->whereNotNull('tr_purchase_non.approved_at')->whereBetween('tr_purchase_non.date', [$this->start_date, $this->end_date]);
            $purchaseNonTax->orWhere('ms_suppliers.company_name', 'like', "%" . $this->searchKeyword . "%")->whereNotNull('tr_purchase_non.approved_at')->whereBetween('tr_purchase_non.date', [$this->start_date, $this->end_date]);
            $purchaseNonTax->orWhere('total', 'like', "%" . $this->searchKeyword . "%")->whereNotNull('tr_purchase_non.approved_at')->whereBetween('tr_purchase_non.date', [$this->start_date, $this->end_date]);
            $purchaseNonTax->orWhere('payment', 'like', "%" . $this->searchKeyword . "%")->whereNotNull('tr_purchase_non.approved_at')->whereBetween('tr_purchase_non.date', [$this->start_date, $this->end_date]);
            $purchaseNonTax->orWhere('rest', 'like', "%" . $this->searchKeyword . "%")->whereNotNull('tr_purchase_non.approved_at')->whereBetween('tr_purchase_non.date', [$this->start_date, $this->end_date]);

            $purchaseTaxSummary->orWhere('number', 'like', "%" . $this->searchKeyword . "%")->whereNotNull('tr_purchase.approved_at')->whereBetween('tr_purchase.date', [$this->start_date, $this->end_date]);
            $purchaseTaxSummary->orWhere('ms_suppliers.company_name', 'like', "%" . $this->searchKeyword . "%")->whereNotNull('tr_purchase.approved_at')->whereBetween('tr_purchase.date', [$this->start_date, $this->end_date]);
            $purchaseTaxSummary->orWhere('total', 'like', "%" . $this->searchKeyword . "%")->whereNotNull('tr_purchase.approved_at')->whereBetween('tr_purchase.date', [$this->start_date, $this->end_date]);
            $purchaseTaxSummary->orWhere('payment', 'like', "%" . $this->searchKeyword . "%")->whereNotNull('tr_purchase.approved_at')->whereBetween('tr_purchase.date', [$this->start_date, $this->end_date]);
            $purchaseTaxSummary->orWhere('rest', 'like', "%" . $this->searchKeyword . "%")->whereNotNull('tr_purchase.approved_at')->whereBetween('tr_purchase.date', [$this->start_date, $this->end_date]);

            $purchaseNonTaxSummary->orWhere('number', 'like', "%" . $this->searchKeyword . "%")->whereNotNull('tr_purchase_non.approved_at')->whereBetween('tr_purchase_non.date', [$this->start_date, $this->end_date]);
            $purchaseNonTaxSummary->orWhere('ms_suppliers.company_name', 'like', "%" . $this->searchKeyword . "%")->whereNotNull('tr_purchase_non.approved_at')->whereBetween('tr_purchase_non.date', [$this->start_date, $this->end_date]);
            $purchaseNonTaxSummary->orWhere('total', 'like', "%" . $this->searchKeyword . "%")->whereNotNull('tr_purchase_non.approved_at')->whereBetween('tr_purchase_non.date', [$this->start_date, $this->end_date]);
            $purchaseNonTaxSummary->orWhere('payment', 'like', "%" . $this->searchKeyword . "%")->whereNotNull('tr_purchase_non.approved_at')->whereBetween('tr_purchase_non.date', [$this->start_date, $this->end_date]);
            $purchaseNonTaxSummary->orWhere('rest', 'like', "%" . $this->searchKeyword . "%")->whereNotNull('tr_purchase_non.approved_at')->whereBetween('tr_purchase_non.date', [$this->start_date, $this->end_date]);
        }

        $purchaseSummary = DB::query()
            ->selectRaw('SUM(total_payment) as total_payment, SUM(paid) as paid, SUM(unpaid) as unpaid')
            ->fromSub($purchaseTaxSummary->unionAll($purchaseNonTaxSummary), 'combined_summary')
            ->first();

        $purchases = $purchaseTax->union($purchaseNonTax)->orderBy($this->sortColumn, $this->sortOrder);
        $purchases = $purchases->paginate($this->perPage);

        return view('livewire.reports.pay-report-manager', compact('purchases', 'purchaseSummary'));
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

        $url = route('print.pay', $responseData);
        $this->dispatchBrowserEvent('openTab', ['url' => $url]);
    }
}
