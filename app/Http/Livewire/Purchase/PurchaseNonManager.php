<?php

namespace App\Http\Livewire\Purchase;

use App\Models\TrPurchaseNon;
use Livewire\Component;
use Livewire\WithPagination;

class PurchaseNonManager extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $perPage = 10;
    public $sortColumn = "tr_purchase_non.number";
    public $sortOrder = "desc";
    public $sortLink = '<i class="sorticon fa-solid fa-caret-up"></i>';
    public $searchKeyword = '';
    public $roleFilter = '';
    public $set_id;

    public function render()
    {
        $queryPurchase = TrPurchaseNon::orderBy($this->sortColumn, $this->sortOrder)
            ->leftJoin('ms_suppliers', 'ms_suppliers.id', '=', 'tr_purchase_non.supplier_id')
            ->select('tr_purchase_non.id', 'tr_purchase_non.number', 'tr_purchase_non.date', 'tr_purchase_non.supplier_id', 'ms_suppliers.company_name as supplier_name', 'tr_purchase_non.reference', 'tr_purchase_non.total', 'tr_purchase_non.notes', 'tr_purchase_non.is_payed', 'tr_purchase_non.is_status');

        if (!empty($this->searchKeyword)) {
            $queryPurchase->orWhere('tr_purchase_non.number', 'like', "%" . $this->searchKeyword . "%");
            $queryPurchase->orWhere('ms_suppliers.company_name', 'like', "%" . $this->searchKeyword . "%");
            $queryPurchase->orWhere('tr_purchase_non.notes', 'like', "%" . $this->searchKeyword . "%");
            $queryPurchase->orWhere('tr_purchase_non.total', 'like', "%" . $this->searchKeyword . "%");
        }

        $purchases = $queryPurchase->paginate($this->perPage);

        return view('livewire.purchase.purchase-non-manager', ['purchases' => $purchases]);
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

    public function edit($id)
    {
        return redirect()->to('/purchase/non-tax/create?id=' . $id);
    }

    public function view($id)
    {
        return redirect()->to('/purchase/non-tax/view/' . $id);
    }
}
