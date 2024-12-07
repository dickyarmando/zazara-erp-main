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
            ->addSelect(DB::raw('"Tax" as type'));

        $purchaseNonTax = TrPurchaseNon::leftJoin('ms_suppliers', 'ms_suppliers.id', '=', 'tr_purchase_non.supplier_id')
            ->select('tr_purchase_non.id', 'tr_purchase_non.number', 'tr_purchase_non.date', 'tr_purchase_non.supplier_id', 'ms_suppliers.company_name as supplier_name', 'tr_purchase_non.reference', 'tr_purchase_non.total', 'tr_purchase_non.payment', 'tr_purchase_non.rest', 'tr_purchase_non.notes', 'tr_purchase_non.is_payed', 'tr_purchase_non.is_status')
            ->addSelect(DB::raw('"Non" as type'));

        $purchases = $purchaseTax->union($purchaseNonTax)->orderBy($this->sortColumn, $this->sortOrder);

        if (!empty($this->searchKeyword)) {
            $purchases->orWhere('number', 'like', "%" . $this->searchKeyword . "%");
            $purchases->orWhere('supplier_name', 'like', "%" . $this->searchKeyword . "%");
            $purchases->orWhere('total', 'like', "%" . $this->searchKeyword . "%");
            $purchases->orWhere('payment', 'like', "%" . $this->searchKeyword . "%");
            $purchases->orWhere('rest', 'like', "%" . $this->searchKeyword . "%");
        }

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
