<?php

namespace App\Http\Livewire\Reports;

use App\Models\TrPurchase;
use App\Models\TrPurchaseNon;
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

    public function render()
    {
        $purchaseTax = TrPurchase::leftJoin('ms_suppliers', 'ms_suppliers.id', '=', 'tr_purchase.supplier_id')
            ->select('tr_purchase.id', 'tr_purchase.number', 'tr_purchase.date', 'tr_purchase.supplier_id', 'ms_suppliers.company_name as supplier_name', 'tr_purchase.reference', 'tr_purchase.total', 'tr_purchase.payment', 'tr_purchase.rest', 'tr_purchase.notes', 'tr_purchase.is_payed', 'tr_purchase.is_status')
            ->addSelect(DB::raw('"Tax" as type'));

        $purchaseNonTax = TrPurchaseNon::leftJoin('ms_suppliers', 'ms_suppliers.id', '=', 'tr_purchase_non.supplier_id')
            ->select('tr_purchase_non.id', 'tr_purchase_non.number', 'tr_purchase_non.date', 'tr_purchase_non.supplier_id', 'ms_suppliers.company_name as supplier_name', 'tr_purchase_non.reference', 'tr_purchase_non.total', 'tr_purchase_non.payment', 'tr_purchase_non.rest', 'tr_purchase_non.notes', 'tr_purchase_non.is_payed', 'tr_purchase_non.is_status')
            ->addSelect(DB::raw('"Non" as type'));

        // Query summary
        $purchaseTaxSummary = TrPurchase::leftJoin('ms_suppliers', 'ms_suppliers.id', '=', 'tr_purchase.supplier_id')
            ->selectRaw('SUM(total) as total_payment, SUM(payment) as paid, SUM(rest) as unpaid');
        $purchaseNonTaxSummary = TrPurchaseNon::leftJoin('ms_suppliers', 'ms_suppliers.id', '=', 'tr_purchase_non.supplier_id')
            ->selectRaw('SUM(total) as total_payment, SUM(payment) as paid, SUM(rest) as unpaid');

        if (!empty($this->searchKeyword)) {
            $purchaseTax->orWhere('number', 'like', "%" . $this->searchKeyword . "%");
            $purchaseTax->orWhere('ms_suppliers.company_name', 'like', "%" . $this->searchKeyword . "%");
            $purchaseTax->orWhere('total', 'like', "%" . $this->searchKeyword . "%");
            $purchaseTax->orWhere('payment', 'like', "%" . $this->searchKeyword . "%");
            $purchaseTax->orWhere('rest', 'like', "%" . $this->searchKeyword . "%");

            $purchaseNonTax->orWhere('number', 'like', "%" . $this->searchKeyword . "%");
            $purchaseNonTax->orWhere('ms_suppliers.company_name', 'like', "%" . $this->searchKeyword . "%");
            $purchaseNonTax->orWhere('total', 'like', "%" . $this->searchKeyword . "%");
            $purchaseNonTax->orWhere('payment', 'like', "%" . $this->searchKeyword . "%");
            $purchaseNonTax->orWhere('rest', 'like', "%" . $this->searchKeyword . "%");

            $purchaseTaxSummary->orWhere('number', 'like', "%" . $this->searchKeyword . "%");
            $purchaseTaxSummary->orWhere('ms_suppliers.company_name', 'like', "%" . $this->searchKeyword . "%");
            $purchaseTaxSummary->orWhere('total', 'like', "%" . $this->searchKeyword . "%");
            $purchaseTaxSummary->orWhere('payment', 'like', "%" . $this->searchKeyword . "%");
            $purchaseTaxSummary->orWhere('rest', 'like', "%" . $this->searchKeyword . "%");

            $purchaseNonTaxSummary->orWhere('number', 'like', "%" . $this->searchKeyword . "%");
            $purchaseNonTaxSummary->orWhere('ms_suppliers.company_name', 'like', "%" . $this->searchKeyword . "%");
            $purchaseNonTaxSummary->orWhere('total', 'like', "%" . $this->searchKeyword . "%");
            $purchaseNonTaxSummary->orWhere('payment', 'like', "%" . $this->searchKeyword . "%");
            $purchaseNonTaxSummary->orWhere('rest', 'like', "%" . $this->searchKeyword . "%");
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
}
