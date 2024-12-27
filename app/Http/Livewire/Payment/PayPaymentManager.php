<?php

namespace App\Http\Livewire\Payment;

use App\Models\TrPurchase;
use App\Models\TrPurchaseNon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class PayPaymentManager extends Component
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
            ->addSelect(DB::raw('"Tax" as type'))
            ->whereNotNull('tr_purchase.approved_at')
            ->where('is_payed', '0');

        $purchaseNonTax = TrPurchaseNon::leftJoin('ms_suppliers', 'ms_suppliers.id', '=', 'tr_purchase_non.supplier_id')
            ->select('tr_purchase_non.id', 'tr_purchase_non.number', 'tr_purchase_non.date', 'tr_purchase_non.supplier_id', 'ms_suppliers.company_name as supplier_name', 'tr_purchase_non.reference', 'tr_purchase_non.total', 'tr_purchase_non.payment', 'tr_purchase_non.rest', 'tr_purchase_non.notes', 'tr_purchase_non.is_payed', 'tr_purchase_non.is_status')
            ->addSelect(DB::raw('"Non" as type'))
            ->whereNotNull('tr_purchase_non.approved_at')
            ->where('is_payed', '0');

        if (!empty($this->searchKeyword)) {
            $purchaseTax->orWhere('number', 'like', "%" . $this->searchKeyword . "%")->whereNotNull('tr_purchase.approved_at')->where('is_payed', '0');
            $purchaseTax->orWhere('ms_suppliers.company_name', 'like', "%" . $this->searchKeyword . "%")->whereNotNull('tr_purchase.approved_at')->where('is_payed', '0');
            $purchaseTax->orWhere('total', 'like', "%" . $this->searchKeyword . "%")->whereNotNull('tr_purchase.approved_at')->where('is_payed', '0');
            $purchaseTax->orWhere('payment', 'like', "%" . $this->searchKeyword . "%")->whereNotNull('tr_purchase.approved_at')->where('is_payed', '0');
            $purchaseTax->orWhere('rest', 'like', "%" . $this->searchKeyword . "%")->whereNotNull('tr_purchase.approved_at')->where('is_payed', '0');

            $purchaseNonTax->orWhere('number', 'like', "%" . $this->searchKeyword . "%")->whereNotNull('tr_purchase_non.approved_at')->where('is_payed', '0');
            $purchaseNonTax->orWhere('ms_suppliers.company_name', 'like', "%" . $this->searchKeyword . "%")->whereNotNull('tr_purchase_non.approved_at')->where('is_payed', '0');
            $purchaseNonTax->orWhere('total', 'like', "%" . $this->searchKeyword . "%")->whereNotNull('tr_purchase_non.approved_at')->where('is_payed', '0');
            $purchaseNonTax->orWhere('payment', 'like', "%" . $this->searchKeyword . "%")->whereNotNull('tr_purchase_non.approved_at')->where('is_payed', '0');
            $purchaseNonTax->orWhere('rest', 'like', "%" . $this->searchKeyword . "%")->whereNotNull('tr_purchase_non.approved_at')->where('is_payed', '0');
        }

        $purchases = $purchaseTax->union($purchaseNonTax)->orderBy($this->sortColumn, $this->sortOrder);
        $purchases = $purchases->paginate($this->perPage);

        return view('livewire.payment.pay-payment-manager', compact('purchases'));
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
        return redirect()->to('/pay/view/' . $id . '/' . $type);
    }
}
