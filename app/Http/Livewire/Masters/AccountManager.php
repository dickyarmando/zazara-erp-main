<?php

namespace App\Http\Livewire\Masters;

use App\Models\MsAccount;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class AccountManager extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $perPage = 10;
    public $sortColumn = "ms_accounts.code";
    public $sortOrder = "asc";
    public $sortLink = '<i class="sorticon fa-solid fa-caret-up"></i>';
    public $searchKeyword = '';
    public $roleFilter = '';
    public $set_id;

    public function render()
    {
        $queryAccounts = MsAccount::leftJoin('prm_category_accounts', 'prm_category_accounts.id', '=', 'ms_accounts.category_account_id')
            ->orderBy($this->sortColumn, $this->sortOrder)
            ->select('ms_accounts.id', 'ms_accounts.code', 'ms_accounts.name', 'ms_accounts.category_account_id', 'prm_category_accounts.name as category_account_name', 'ms_accounts.account_type', 'ms_accounts.debit', 'ms_accounts.credit', 'ms_accounts.opening_balance', 'ms_accounts.parent_code', 'ms_accounts.level', 'ms_accounts.is_status');

        if (!empty($this->searchKeyword)) {
            $queryAccounts->orWhere('ms_accounts.code', 'like', "%" . $this->searchKeyword . "%")->where('ms_accounts.is_status', '1');
            $queryAccounts->orWhere('ms_accounts.name', 'like', "%" . $this->searchKeyword . "%")->where('ms_accounts.is_status', '1');
            $queryAccounts->orWhere('prm_category_accounts.name', 'like', "%" . $this->searchKeyword . "%")->where('ms_accounts.is_status', '1');
            $queryAccounts->orWhere('ms_accounts.account_type', 'like', "%" . $this->searchKeyword . "%")->where('ms_accounts.is_status', '1');
        }

        $accounts = $queryAccounts->where('ms_accounts.is_status', '1')->paginate($this->perPage);

        return view('livewire.masters.account-manager', compact('accounts'));
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
        return redirect()->to('/masters/accounts/create?id=' . $id);
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

        $tp = MsAccount::find($this->set_id);
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
