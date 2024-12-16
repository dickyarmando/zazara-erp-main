<?php

namespace App\Http\Livewire\Masters;

use App\Models\MsAccount;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class AccountCreateManager extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $sortColumn = "ms_accounts.code";
    public $sortOrder = "asc";
    public $sortLink = '<i class="sorticon fa-solid fa-caret-up"></i>';
    public $searchKeyword = '';
    public $set_id;

    public $code;
    public $name;
    public $category_account_id;
    public $account_type;
    public $debit;
    public $credit;
    public $opening_balance;
    public $parent_code;
    public $parent_code_name;
    public $level;

    public function mount()
    {
        $this->set_id = request()->id;
        if (!empty($this->set_id)) {
            $account = MsAccount::find($this->set_id);
            $this->code = $account->code;
            $this->name = $account->name;
            $this->category_account_id = $account->category_account_id;
            $this->account_type = $account->account_type;
            $this->debit = $account->debit;
            $this->credit = $account->credit;
            $this->opening_balance = $account->opening_balance;
            $this->parent_code = $account->parent_code;
            $this->level = $account->level;

            if ($this->parent_code != null) {
                $parentCode = MsAccount::find($account->parent_code);
                $this->parent_code_name = $parentCode->code . ' - ' . $parentCode->name;
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

        return view('livewire.masters.account-create-manager', compact('accounts'));
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
        return redirect()->to('/masters/accounts');
    }

    public function store()
    {
        $rules = [
            'code' => 'required',
            'name' => 'required',
            'category_account_id' => 'required',
            'account_type' => 'required',
            'opening_balance' => '',
            'parent_code' => '',
            'level' => '',
        ];

        if (empty($this->set_id)) {

            $countAccount = MsAccount::where('code', $this->code)
                ->where('is_status', '1')
                ->orWhere('name', $this->name)
                ->where('is_status', '1')
                ->count();

            if ($countAccount > 0) {
                session()->flash('error', 'Failed, Code or Name Account already exist..');
                return false;
            }

            $valid = $this->validate($rules);
            $valid['opening_balance'] = isset($valid['opening_balance']) ? $valid['opening_balance'] : 0;
            $valid['level'] = isset($valid['level']) ? $valid['level'] : 'G';
            $valid['created_by'] = Auth::user()->id;
            $valid['updated_by'] = Auth::user()->id;
            MsAccount::create($valid);
        } else {

            $countAccount = MsAccount::where('code', $this->code)
                ->where('is_status', '1')
                ->where('id', '!=', $this->set_id)
                ->orWhere('name', $this->name)
                ->where('is_status', '1')
                ->where('id', '!=', $this->set_id)
                ->count();

            if ($countAccount > 0) {
                session()->flash('error', 'Failed, Code or Name Account already exist..');
                return false;
            }

            $valid = $this->validate($rules);
            $valid['opening_balance'] = isset($valid['opening_balance']) ? $valid['opening_balance'] : 0;
            $valid['level'] = isset($valid['level']) ? $valid['level'] : 'G';
            $valid['updated_by'] = Auth::user()->id;
            $account = MsAccount::find($this->set_id);
            $account->update($valid);
        }

        session()->flash('success', 'Saved');
        return redirect()->to('/masters/accounts');
    }

    public function chooseHeader($id)
    {
        $account = MsAccount::find($id);
        $this->parent_code = $account->id;
        $this->parent_code_name = $account->code . ' - ' . $account->name;

        $this->dispatchBrowserEvent('close-modal');
    }

    public function closeModal()
    {
        $this->resetErrorBag();
        $this->resetValidation();
        $this->dispatchBrowserEvent('close-modal');
    }
}
