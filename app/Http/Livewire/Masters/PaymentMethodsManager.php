<?php

namespace App\Http\Livewire\Masters;

use App\Models\MsPaymentMethods;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class PaymentMethodsManager extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $perPage = 10;
    public $sortColumn = "ms_payment_methods.name";
    public $sortOrder = "asc";
    public $sortLink = '<i class="sorticon fa-solid fa-caret-up"></i>';
    public $searchKeyword = '';
    public $roleFilter = '';
    public $set_id;

    public function render()
    {
        $queryPaymentMethods = MsPaymentMethods::orderBy($this->sortColumn, $this->sortOrder)
            ->select('ms_payment_methods.id', 'ms_payment_methods.name', 'ms_payment_methods.account_id', 'ms_payment_methods.is_status');

        if (!empty($this->searchKeyword)) {
            $queryPaymentMethods->orWhere('ms_payment_methods.name', 'like', "%" . $this->searchKeyword . "%")->where('ms_payment_methods.is_status', '1');
        }

        $paymentMethods = $queryPaymentMethods->where('ms_payment_methods.is_status', '1')->paginate($this->perPage);

        return view('livewire.masters.payment-methods-manager', compact('paymentMethods'));
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
        return redirect()->to('/masters/payment-methods/create?id=' . $id);
    }

    public function delete($id)
    {
        $this->set_id = $id;
    }

    public function destroy()
    {
        $valid = [
            'is_status' => '0'
        ];

        $tp = MsPaymentMethods::find($this->set_id);
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
