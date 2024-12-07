<?php

namespace App\Http\Livewire\Sales;

use App\Models\TrSales;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class SalesManager extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $perPage = 10;
    public $sortColumn = "tr_sales.number";
    public $sortOrder = "desc";
    public $sortLink = '<i class="sorticon fa-solid fa-caret-up"></i>';
    public $searchKeyword = '';
    public $roleFilter = '';
    public $set_id;

    public function render()
    {
        $querySales = TrSales::orderBy($this->sortColumn, $this->sortOrder)
            ->leftJoin('ms_customers', 'ms_customers.id', '=', 'tr_sales.customer_id')
            ->select('tr_sales.id', 'tr_sales.number', 'tr_sales.date', 'tr_sales.customer_id', 'ms_customers.company_name as customer_name', 'tr_sales.reference', 'tr_sales.total', 'tr_sales.notes', 'tr_sales.is_receive', 'tr_sales.is_status')
            ->addSelect([
                'total_payment' => DB::table('tr_receives')
                    ->selectRaw('COUNT(*)')
                    ->whereColumn('tr_receives.sales_id', 'tr_sales.id')
                    ->where('tr_receives.sales_type', '1')
                    ->limit(1)
            ]);

        if (!empty($this->searchKeyword)) {
            $querySales->orWhere('tr_sales.number', 'like', "%" . $this->searchKeyword . "%");
            $querySales->orWhere('ms_customers.company_name', 'like', "%" . $this->searchKeyword . "%");
            $querySales->orWhere('tr_sales.notes', 'like', "%" . $this->searchKeyword . "%");
            $querySales->orWhere('tr_sales.total', 'like', "%" . $this->searchKeyword . "%");
        }

        $saless = $querySales->paginate($this->perPage);

        return view('livewire.sales.sales-manager', ['saless' => $saless]);
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
        return redirect()->to('/sales/create?id=' . $id);
    }

    public function view($id)
    {
        return redirect()->to('/sales/view/' . $id);
    }
}
