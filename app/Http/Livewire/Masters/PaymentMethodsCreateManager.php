<?php

namespace App\Http\Livewire\Masters;

use App\Models\MsAccount;
use App\Models\MsPaymentMethods;
use Livewire\Component;
use Livewire\WithPagination;

class PaymentMethodsCreateManager extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $sortColumn = "ms_accounts.code";
    public $sortOrder = "asc";
    public $sortLink = '<i class="sorticon fa-solid fa-caret-up"></i>';
    public $searchKeyword = '';
    public $set_id;

    public $name;
    public $account_id;
    public $account_name;

    public function mount()
    {
        $this->set_id = request()->id;
        if (!empty($this->set_id)) {
            $paymentMethods = MsPaymentMethods::find($this->set_id);
            $this->name = $paymentMethods->name;
            $this->account_id = $paymentMethods->account_id;

            if ($this->account_id != null) {
                $accounts = MsAccount::find($paymentMethods->account_id);
                $this->account_name = $accounts->code . ' - ' . $accounts->name;
            }
        }
    }

    public function render()
    {
        $queryAccounts = MsAccount::orderby($this->sortColumn, $this->sortOrder)
            ->leftJoin('prm_category_accounts', 'prm_category_accounts.id', '=', 'ms_accounts.category_account_id')
            ->select('ms_accounts.id', 'ms_accounts.code', 'ms_accounts.name', 'ms_accounts.category_account_id', 'prm_category_accounts.name as category_account_name', 'ms_accounts.account_type', 'ms_accounts.debit', 'ms_accounts.credit', 'ms_accounts.opening_balance', 'ms_accounts.parent_code', 'ms_accounts.level', 'ms_accounts.is_status');
        if (!empty($this->searchKeyword)) {
            $queryAccounts->orWhere('ms_accounts.code', 'like', "%" . $this->searchKeyword . "%")->where('ms_accounts.is_status', '1');
            $queryAccounts->orWhere('ms_accounts.name', 'like', "%" . $this->searchKeyword . "%")->where('ms_accounts.is_status', '1');
            $queryAccounts->orWhere('prm_category_accounts.name', 'like', "%" . $this->searchKeyword . "%")->where('ms_accounts.is_status', '1');
            $queryAccounts->orWhere('ms_accounts.account_type', 'like', "%" . $this->searchKeyword . "%")->where('ms_accounts.is_status', '1');
        }
        $accounts = $queryAccounts->where('ms_accounts.is_status', '1')->paginate(10);

        return view('livewire.masters.payment-methods-create-manager', compact('accounts'));
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

    public function backRedirect()
    {
        return redirect()->to('/masters/payment-methods');
    }

    public function store()
    {
        $rules = [
            'name' => 'required',
            'account_id' => '',
        ];

        if (empty($this->set_id)) {

            $countPaymentMethods = MsPaymentMethods::where('name', $this->name)
                ->where('is_status', '1')
                ->count();

            if ($countPaymentMethods > 0) {
                session()->flash('error', 'Failed, Payment Method already exist..');
                return false;
            }

            $valid = $this->validate($rules);
            MsPaymentMethods::create($valid);
        } else {

            $countPaymentMethods = MsPaymentMethods::where('name', $this->name)
                ->where('is_status', '1')
                ->where('id', '!=', $this->set_id)
                ->count();

            if ($countPaymentMethods > 0) {
                session()->flash('error', 'Failed, Payment Method already exist..');
                return false;
            }

            $valid = $this->validate($rules);
            $paymentMethods = MsPaymentMethods::find($this->set_id);
            $paymentMethods->update($valid);
        }

        session()->flash('success', 'Saved');
        return redirect()->to('/masters/payment-methods');
    }

    public function chooseAccount($id)
    {
        $account = MsAccount::find($id);
        $this->account_id = $account->id;
        $this->account_name = $account->code . ' - ' . $account->name;

        $this->dispatchBrowserEvent('close-modal');
    }

    public function closeModal()
    {
        $this->resetErrorBag();
        $this->resetValidation();
        $this->dispatchBrowserEvent('close-modal');
    }
}
