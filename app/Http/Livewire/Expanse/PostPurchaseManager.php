<?php

namespace App\Http\Livewire\Expanse;

use App\Models\PrmConfig;
use App\Models\TrGeneralLedger;
use App\Models\TrGeneralLedgerDetails;
use App\Models\TrPurchase;
use App\Models\TrPurchaseNon;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class PostPurchaseManager extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $perPage = 10;
    public $sortColumn = "date";
    public $sortOrder = "desc";
    public $sortLink = '<i class="sorticon fa-solid fa-caret-up"></i>';
    public $searchKeyword = '';
    public $roleFilter = '';
    public $set_id;

    public $selected = [];
    public $selectAll = false;
    public $purchasesPostMultiple = [];

    public function render()
    {
        $purchaseTax = TrPurchase::leftJoin('ms_suppliers', 'ms_suppliers.id', '=', 'tr_purchase.supplier_id')
            ->select('tr_purchase.id', 'tr_purchase.number', 'tr_purchase.date', 'tr_purchase.supplier_id', 'ms_suppliers.company_name as supplier_name', 'tr_purchase.reference', 'tr_purchase.total', 'tr_purchase.payment', 'tr_purchase.rest', 'tr_purchase.notes', 'tr_purchase.is_payed', 'tr_purchase.is_status', 'tr_purchase.approved_at')
            ->addSelect(DB::raw('"Tax" as type'))
            ->whereNotNull('tr_purchase.approved_at')
            ->where('is_posting', '0');

        if (!empty($this->searchKeyword)) {
            $purchaseTax->orWhere('number', 'like', "%" . $this->searchKeyword . "%")->whereNotNull('tr_purchase.approved_at')->where('is_posting', '0');
            $purchaseTax->orWhere('ms_suppliers.company_name', 'like', "%" . $this->searchKeyword . "%")->whereNotNull('tr_purchase.approved_at')->where('is_posting', '0');
            $purchaseTax->orWhere('total', 'like', "%" . $this->searchKeyword . "%")->whereNotNull('tr_purchase.approved_at')->where('is_posting', '0');
            $purchaseTax->orWhere('approved_at', 'like', "%" . $this->searchKeyword . "%")->whereNotNull('tr_purchase.approved_at')->where('is_posting', '0');
        }

        $purchases = $purchaseTax->paginate($this->perPage);

        return view('livewire.expanse.post-purchase-manager', compact('purchases'));
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

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selected = $this->getAllPurchaseIds();
        } else {
            $this->selected = [];
        }
    }

    public function updatedSelected()
    {
        if (count($this->selected) === $this->getAllPurchaseIds()->count()) {
            $this->selectAll = true;
            $this->dispatchBrowserEvent('checkall-indeterminate-false');
            $this->dispatchBrowserEvent('checkall-checked');
        } elseif (count($this->selected) === 0) {
            $this->selectAll = false;
            $this->dispatchBrowserEvent('checkall-checked-false');
            $this->dispatchBrowserEvent('checkall-indeterminate-false');
        } else {
            $this->dispatchBrowserEvent('checkall-indeterminate');
        }
    }

    private function getAllPurchaseIds()
    {
        $purchaseTax = TrPurchase::whereNotNull('approved_at')->where('is_posting', '0')->pluck('id');
        return $purchaseTax;
    }

    public function postMultiple()
    {
        if (count($this->selected) <= 0) {
            session()->flash('error', 'Please select at least one purchase');
            $this->closeModal();
        }

        $purchases = TrPurchase::whereIn('id', $this->selected)
            ->select('id', 'number', 'subtotal', 'delivery_fee', 'discount', 'ppn', 'ppn_amount', 'total')
            ->addSelect(DB::raw('"Tax" as type'))
            ->get()
            ->toArray();

        $this->purchasesPostMultiple = $purchases;
    }

    public function closeModal()
    {
        $this->resetErrorBag();
        $this->resetValidation();
        $this->dispatchBrowserEvent('close-modal');
    }

    public function formReset()
    {
        $this->selected = [];
        $this->selectAll = false;
        $this->purchasesPostMultiple = [];

        $this->closeModal();
        $this->dispatchBrowserEvent('checkall-indeterminate-false');
    }

    public function store()
    {
        $now = Carbon::now();
        $payableAccount = PrmConfig::where('code', 'coapa')->get()->first();
        $purchaseAccount = PrmConfig::where('code', 'coap')->get()->first();
        $ppnAccount = PrmConfig::where('code', 'coapp')->get()->first();

        if (!isset($payableAccount->value) || !isset($purchaseAccount->value) || !isset($ppnAccount->value)) {
            session()->flash('error', 'Please set payable account, purchase account and ppn purchase account');
            $this->closeModal();
            return false;
        } else {
            if ($payableAccount->value == '' || $purchaseAccount->value == '' || $ppnAccount->value == '') {
                session()->flash('error', 'Please set payable account, purchase account and ppn purchase account');
                $this->closeModal();
                return false;
            }
        }

        foreach ($this->purchasesPostMultiple as $key => $val) {

            $month = $now->month;
            $year = $now->year;

            $countGL = TrGeneralLedger::where(DB::raw('MONTH(date)'), $month)
                ->where(DB::raw('YEAR(date)'), $year)
                ->count();
            $sequence = $countGL + 1;
            $number = str_pad($sequence, 4, "0", STR_PAD_LEFT);
            $numberExpanse = 'EX/ESB/' . $month . $year . '/' . $number;

            $dataGeneralLedger = [
                'number' => $numberExpanse,
                'date' => $now->toDateString(),
                'reference' => $val['number'],
                'notes' => "Purchase from PO : " . $val['number'],
                'total_debit' => $val['total'],
                'total_credit' => $val['total'],
                'created_by' => Auth::user()->id,
                'updated_by' => Auth::user()->id,
            ];
            $gl = TrGeneralLedger::create($dataGeneralLedger);

            $totalPurchase = $val['total'] - $val['ppn_amount'];

            $dataGeneralLedgerDetails = [
                'general_ledger_id' => $gl->id,
                'account_id' => $purchaseAccount->value,
                'type' => 'db',
                'amount' => $totalPurchase,
                'created_by' => Auth::user()->id,
                'updated_by' => Auth::user()->id,
            ];
            TrGeneralLedgerDetails::create($dataGeneralLedgerDetails);

            $dataGeneralLedgerDetailsPPN = [
                'general_ledger_id' => $gl->id,
                'account_id' => $ppnAccount->value,
                'type' => 'db',
                'amount' => $val['ppn_amount'],
                'created_by' => Auth::user()->id,
                'updated_by' => Auth::user()->id,
            ];
            TrGeneralLedgerDetails::create($dataGeneralLedgerDetailsPPN);

            $dataGeneralLedgerDetailsPayable = [
                'general_ledger_id' => $gl->id,
                'account_id' => $payableAccount->value,
                'type' => 'cr',
                'amount' => $val['total'],
                'created_by' => Auth::user()->id,
                'updated_by' => Auth::user()->id,
            ];
            TrGeneralLedgerDetails::create($dataGeneralLedgerDetailsPayable);

            $dataPurchase = [
                'is_posting' => '1',
                'updated_by' => Auth::user()->id,
            ];

            TrPurchase::find($val['id'])->update($dataPurchase);
        }

        $this->formReset();
        session()->flash('success', 'Saved');
    }
}
