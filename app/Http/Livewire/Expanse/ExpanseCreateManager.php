<?php

namespace App\Http\Livewire\Expanse;

use App\Models\MsAccount;
use App\Models\TrGeneralLedger;
use App\Models\TrGeneralLedgerDetails;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class ExpanseCreateManager extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $sortColumn = "ms_accounts.code";
    public $sortOrder = "asc";
    public $sortLink = '<i class="sorticon fa-solid fa-caret-up"></i>';
    public $searchKeyword = '';
    public $set_id;
    public $set_index;

    public $month;
    public $year;
    public $sequence;

    public $number;
    public $date;
    public $reference;
    public $notes;
    public $total_credit;
    public $total_debit;

    public $items = [];

    public function mount()
    {
        $now = Carbon::now();
        $this->month = $now->month;
        $this->year = $now->year;

        $countGL = TrGeneralLedger::where(DB::raw('MONTH(date)'), $this->month)
            ->where(DB::raw('YEAR(date)'), $this->year)
            ->count();
        $this->sequence = $countGL + 1;
        $this->number = str_pad($this->sequence, 4, "0", STR_PAD_LEFT);
        $this->date = $now->format('Y-m-d');

        $this->total_debit = 0;
        $this->total_credit = 0;

        $this->add('cr');
        $this->add('db');
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

        $this->calculateTotal();

        return view('livewire.expanse.expanse-create-manager', compact('accounts'));
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

    public function add($type)
    {
        $this->items[] = [
            'account_id' => '',
            'account_name' => '',
            'debit' => 0,
            'credit' => 0
        ];
    }

    public function closeModal()
    {
        $this->resetErrorBag();
        $this->resetValidation();
        $this->dispatchBrowserEvent('close-modal');
    }

    public function setIndex($index)
    {
        $this->set_index = $index;
    }

    public function chooseAccount($id)
    {
        $accounts = MsAccount::find($id);
        $this->items[$this->set_index]['account_id'] = $id;
        $this->items[$this->set_index]['account_name'] = $accounts->code . ' - ' . $accounts->name;

        $this->resetForm();
        $this->closeModal();
    }

    public function resetForm()
    {
        $this->set_index = null;
    }

    public function calculateTotal()
    {
        $this->total_debit = 0;
        $this->total_credit = 0;
        foreach ($this->items as $item) {
            $this->total_debit += $item['debit'];
            $this->total_credit += $item['credit'];
        }

        if ($this->total_debit == $this->total_credit) {
            $this->dispatchBrowserEvent('balance');
        } else {
            $this->dispatchBrowserEvent('unbalance');
        }
    }

    public function store()
    {
        $rules = [
            'number' => 'required',
            'date' => 'required',
            'reference' => '',
            'notes' => '',
        ];

        if (empty($this->set_id)) {

            $numberOrder = 'EX/ESB/' . $this->month . $this->year . '/' . $this->number;

            $countNumber = TrGeneralLedger::where('number', $numberOrder)->count();

            if ($countNumber > 0) {
                $countGL = TrGeneralLedger::where(DB::raw('MONTH(created_at)'), $this->month)
                    ->where(DB::raw('YEAR(created_at)'), $this->year)
                    ->count();
                $this->sequence = $countGL + 1;
                $this->number = str_pad($this->sequence, 4, "0", STR_PAD_LEFT);
                $numberOrder = 'EX/ESB/' . $this->month . $this->year . '/' . $this->number;
            }

            $valid = $this->validate($rules);
            $valid['number'] = $numberOrder;
            $valid['total_debit'] = $this->total_debit;
            $valid['total_credit'] = $this->total_credit;
            $valid['created_by'] = Auth::user()->id;
            $valid['updated_by'] = Auth::user()->id;
            $gl = TrGeneralLedger::create($valid);

            foreach ($this->items as $key => $item) {
                if ($item['account_id'] != "") {
                    $type = $item['debit'] > 0 ? 'db' : 'cr';
                    $amount = $item['debit'] > 0 ? $item['debit'] : $item['credit'];

                    $dataDetail = [
                        'general_ledger_id' => $gl->id,
                        'account_id' => $item['account_id'],
                        'type' => $type,
                        'amount' => $amount,
                    ];

                    TrGeneralLedgerDetails::create($dataDetail);
                }
            }
        } else {

            $valid = $this->validate($rules);
            $valid['total_debit'] = $this->total_debit;
            $valid['total_credit'] = $this->total_credit;
            $valid['updated_by'] = Auth::user()->id;
            $gl = TrGeneralLedger::find($this->set_id);
            $gl->update($valid);

            TrGeneralLedgerDetails::where('general_ledger_id', $this->set_id)->delete();
            foreach ($this->items as $key => $item) {
                if ($item['account_id'] != "") {
                    $type = $item['debit'] > 0 ? 'db' : 'cr';
                    $amount = $item['debit'] > 0 ? $item['debit'] : $item['credit'];

                    $dataDetail = [
                        'general_ledger_id' => $this->set_id,
                        'account_id' => $item['account_id'],
                        'type' => $type,
                        'amount' => $amount,
                    ];

                    TrGeneralLedgerDetails::create($dataDetail);
                }
            }
        }

        session()->flash('success', 'Saved');
        return redirect()->to('/expanse/create');
    }
}
