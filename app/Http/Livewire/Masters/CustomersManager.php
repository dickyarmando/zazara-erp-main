<?php

namespace App\Http\Livewire\Masters;

use App\Models\MsCustomers;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class CustomersManager extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $perPage = 10;
    public $sortColumn = "ms_customers.company_name";
    public $sortOrder = "asc";
    public $sortLink = '<i class="sorticon fa-solid fa-caret-up"></i>';
    public $searchKeyword = '';
    public $roleFilter = '';
    public $set_id;

    public function render()
    {
        $queryCustomers = MsCustomers::orderBy($this->sortColumn, $this->sortOrder)
            ->select('ms_customers.id', 'ms_customers.code', 'ms_customers.name', 'ms_customers.company_name', 'ms_customers.address', 'ms_customers.email', 'ms_customers.phone', 'ms_customers.telephone', 'ms_customers.fax', 'ms_customers.is_status');

        if (!empty($this->searchKeyword)) {
            $queryCustomers->orWhere('ms_customers.code', 'like', "%" . $this->searchKeyword . "%");
            $queryCustomers->orWhere('ms_customers.name', 'like', "%" . $this->searchKeyword . "%");
            $queryCustomers->orWhere('ms_customers.company_name', 'like', "%" . $this->searchKeyword . "%");
            $queryCustomers->orWhere('ms_customers.address', 'like', "%" . $this->searchKeyword . "%");
            $queryCustomers->orWhere('ms_customers.email', 'like', "%" . $this->searchKeyword . "%");
            $queryCustomers->orWhere('ms_customers.phone', 'like', "%" . $this->searchKeyword . "%");
            $queryCustomers->orWhere('ms_customers.telephone', 'like', "%" . $this->searchKeyword . "%");
            $queryCustomers->orWhere('ms_customers.fax', 'like', "%" . $this->searchKeyword . "%");
        }

        $customers = $queryCustomers->paginate($this->perPage);

        return view('livewire.masters.customers-manager', ['customers' => $customers]);
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
        return redirect()->to('/masters/customers/create?id=' . $id);
    }

    public function delete($id)
    {
        $this->set_id = $id;
    }

    public function destroy()
    {
        $valid = [
            'is_status' => '0',
            'updated_at' => Carbon::now()->toDateTimeString(),
            'updated_by' => Auth::user()->id
        ];

        $tp = MsCustomers::find($this->set_id);
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

    public function actived()
    {
        $valid = [
            'is_status' => '1',
            'updated_at' => Carbon::now()->toDateTimeString(),
            'updated_by' => Auth::user()->id
        ];

        $tp = MsCustomers::find($this->set_id);
        $tp->update($valid);

        $this->formReset();
        session()->flash('success', 'Actived');
        $this->dispatchBrowserEvent('close-modal');
    }
}
