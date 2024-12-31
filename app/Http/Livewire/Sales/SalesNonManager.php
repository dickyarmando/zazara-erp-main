<?php

namespace App\Http\Livewire\Sales;

use App\Models\PrmRoleMenus;
use App\Models\TrSalesNon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class SalesNonManager extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $perPage = 10;
    public $sortColumn = "tr_sales_non.number";
    public $sortOrder = "desc";
    public $sortLink = '<i class="sorticon fa-solid fa-caret-up"></i>';
    public $searchKeyword = '';
    public $roleFilter = '';
    public $set_id;
    public $userRoles = [];
    public $userRolesReceives = [];


    public function mount()
    {
        $this->userRoles = PrmRoleMenus::where('menu_id', '31')->where('role_id', Auth::user()->role_id)->first();
        $this->userRolesReceives = PrmRoleMenus::where('menu_id', '11')->where('role_id', Auth::user()->role_id)->first();
    }

    public function render()
    {
        $querySales = TrSalesNon::orderBy($this->sortColumn, $this->sortOrder)
            ->leftJoin('ms_customers', 'ms_customers.id', '=', 'tr_sales_non.customer_id')
            ->leftJoin('tr_invoices_nons', 'tr_invoices_nons.sales_non_id', '=', 'tr_sales_non.id')
            ->select('tr_sales_non.id', 'tr_sales_non.number', 'tr_sales_non.date', 'tr_sales_non.customer_id', 'ms_customers.company_name as customer_name', 'tr_sales_non.reference', 'tr_sales_non.total', 'tr_sales_non.notes', 'tr_sales_non.is_receive', 'tr_sales_non.is_status', 'tr_sales_non.approved_at', 'tr_sales_non.approved_by', 'tr_sales_non.is_invoice', 'tr_invoices_nons.id as invoice_id')
            ->addSelect([
                'total_payment' => DB::table('tr_receives')
                    ->selectRaw('COUNT(*)')
                    ->whereColumn('tr_receives.sales_id', 'tr_sales_non.id')
                    ->where('tr_receives.sales_type', '2')
                    ->limit(1)
            ]);

        if (!empty($this->searchKeyword)) {
            $querySales->orWhere('tr_sales_non.number', 'like', "%" . $this->searchKeyword . "%");
            $querySales->orWhere('ms_customers.company_name', 'like', "%" . $this->searchKeyword . "%");
            $querySales->orWhere('tr_sales_non.notes', 'like', "%" . $this->searchKeyword . "%");
            $querySales->orWhere('tr_sales_non.total', 'like', "%" . $this->searchKeyword . "%");
        }

        $saless = $querySales->paginate($this->perPage);

        return view('livewire.sales.sales-non-manager', ['saless' => $saless]);
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
        return redirect()->to('/sales/non-tax/create?id=' . $id);
    }

    public function view($id)
    {
        return redirect()->to('/sales/non-tax/view/' . $id);
    }

    public function createInvoice($id)
    {
        return redirect()->to('/sales/non-tax/invoice/create/' . $id);
    }

    public function viewInvoice($id)
    {
        return redirect()->to('/sales/non-tax/invoice/view/' . $id);
    }
}
