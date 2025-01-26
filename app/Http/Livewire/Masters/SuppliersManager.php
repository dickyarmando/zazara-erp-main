<?php

namespace App\Http\Livewire\Masters;

use App\Models\MsSuppliers;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class SuppliersManager extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $perPage = 10;
    public $sortColumn = "ms_suppliers.company_name";
    public $sortOrder = "asc";
    public $sortLink = '<i class="sorticon fa-solid fa-caret-up"></i>';
    public $searchKeyword = '';
    public $roleFilter = '';
    public $set_id;

    public function render()
    {
        $querySuppliers = MsSuppliers::orderBy($this->sortColumn, $this->sortOrder)
            ->select('ms_suppliers.id', 'ms_suppliers.code', 'ms_suppliers.name', 'ms_suppliers.company_name', 'ms_suppliers.address', 'ms_suppliers.email', 'ms_suppliers.phone', 'ms_suppliers.telephone', 'ms_suppliers.fax');

        if (!empty($this->searchKeyword)) {
            $querySuppliers->orWhere('ms_suppliers.code', 'like', "%" . $this->searchKeyword . "%")->where('ms_suppliers.is_status', '1');
            $querySuppliers->orWhere('ms_suppliers.name', 'like', "%" . $this->searchKeyword . "%")->where('ms_suppliers.is_status', '1');
            $querySuppliers->orWhere('ms_suppliers.company_name', 'like', "%" . $this->searchKeyword . "%")->where('ms_suppliers.is_status', '1');
            $querySuppliers->orWhere('ms_suppliers.address', 'like', "%" . $this->searchKeyword . "%")->where('ms_suppliers.is_status', '1');
            $querySuppliers->orWhere('ms_suppliers.email', 'like', "%" . $this->searchKeyword . "%")->where('ms_suppliers.is_status', '1');
            $querySuppliers->orWhere('ms_suppliers.phone', 'like', "%" . $this->searchKeyword . "%")->where('ms_suppliers.is_status', '1');
            $querySuppliers->orWhere('ms_suppliers.telephone', 'like', "%" . $this->searchKeyword . "%")->where('ms_suppliers.is_status', '1');
            $querySuppliers->orWhere('ms_suppliers.fax', 'like', "%" . $this->searchKeyword . "%")->where('ms_suppliers.is_status', '1');
        }

        $suppliers = $querySuppliers->where('ms_suppliers.is_status', '1')->paginate($this->perPage);

        return view('livewire.masters.suppliers-manager', ['suppliers' => $suppliers]);
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
        return redirect()->to('/masters/suppliers/create?id=' . $id);
    }

    public function delete($id)
    {
        $this->set_id = $id;
    }

    public function destroy()
    {
        $valid = [
            'is_status' => '0',
            'deleted_at' => Carbon::now()->toDateTimeString(),
            'deleted_by' => Auth::user()->id
        ];

        $tp = MsSuppliers::find($this->set_id);
        $tp->update($valid);

        $this->formReset();
        session()->flash('success', 'Deleted');
        $this->dispatchBrowserEvent('close-modal');
    }

    public function formReset()
    {
        $this->set_id = null;

        $this->resetErrorBag();
        $this->resetValidation();
    }
}
