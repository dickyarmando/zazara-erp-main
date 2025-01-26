<?php

namespace App\Http\Livewire\Purchase;

use App\Models\PrmRoleMenus;
use App\Models\TrPurchaseNon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
    public $userRoles = [];

    public function mount()
    {
        $this->userRoles = PrmRoleMenus::where('menu_id', '29')->where('role_id', Auth::user()->role_id)->first();
    }

    public function render()
    {
        $queryPurchase = TrPurchaseNon::orderBy($this->sortColumn, $this->sortOrder)
            ->leftJoin('ms_suppliers', 'ms_suppliers.id', '=', 'tr_purchase_non.supplier_id')
            ->select('tr_purchase_non.id', 'tr_purchase_non.number', 'tr_purchase_non.date', 'tr_purchase_non.supplier_id', 'ms_suppliers.company_name as supplier_name', 'tr_purchase_non.reference', 'tr_purchase_non.total', 'tr_purchase_non.notes', 'tr_purchase_non.is_payed', 'tr_purchase_non.is_status', 'tr_purchase_non.approved_at', 'tr_purchase_non.approved_by')
            ->addSelect([
                'total_payment' => DB::table('tr_payments')
                    ->selectRaw('COUNT(*)')
                    ->whereColumn('tr_payments.purchase_id', 'tr_purchase_non.id')
                    ->where('tr_payments.purchase_type', '2')
                    ->limit(1)
            ]);

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
