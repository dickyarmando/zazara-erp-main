<?php

namespace App\Http\Livewire\Purchase;

use App\Models\PrmRoleMenus;
use App\Models\TrPurchase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class PurchaseManager extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $perPage = 10;
    public $sortColumn = "tr_purchase.number";
    public $sortOrder = "desc";
    public $sortLink = '<i class="sorticon fa-solid fa-caret-up"></i>';
    public $searchKeyword = '';
    public $roleFilter = '';
    public $set_id;
    public $userRoles = [];

    public function mount()
    {
        $this->userRoles = PrmRoleMenus::where('menu_id', '28')->where('role_id', Auth::user()->role_id)->first();
    }

    public function render()
    {
        $queryPurchase = TrPurchase::orderBy($this->sortColumn, $this->sortOrder)
            ->leftJoin('ms_suppliers', 'ms_suppliers.id', '=', 'tr_purchase.supplier_id')
            ->select('tr_purchase.id', 'tr_purchase.number', 'tr_purchase.date', 'tr_purchase.supplier_id', 'ms_suppliers.company_name as supplier_name', 'tr_purchase.reference', 'tr_purchase.total', 'tr_purchase.notes', 'tr_purchase.is_payed', 'tr_purchase.is_status', 'tr_purchase.approved_at', 'tr_purchase.approved_by')
            ->addSelect([
                'total_payment' => DB::table('tr_payments')
                    ->selectRaw('COUNT(*)')
                    ->whereColumn('tr_payments.purchase_id', 'tr_purchase.id')
                    ->where('tr_payments.purchase_type', '1')
                    ->limit(1)
            ]);

        if (!empty($this->searchKeyword)) {
            $queryPurchase->orWhere('tr_purchase.number', 'like', "%" . $this->searchKeyword . "%");
            $queryPurchase->orWhere('tr_purchase.reference', 'like', "%" . $this->searchKeyword . "%");
            $queryPurchase->orWhere('ms_suppliers.company_name', 'like', "%" . $this->searchKeyword . "%");
            $queryPurchase->orWhere('tr_purchase.notes', 'like', "%" . $this->searchKeyword . "%");
            $queryPurchase->orWhere('tr_purchase.total', 'like', "%" . $this->searchKeyword . "%");
        }

        $purchases = $queryPurchase->paginate($this->perPage);

        return view('livewire.purchase.purchase-manager', ['purchases' => $purchases]);
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
        return redirect()->to('/purchase/create?id=' . $id);
    }

    public function view($id)
    {
        return redirect()->to('/purchase/view/' . $id);
    }
}
