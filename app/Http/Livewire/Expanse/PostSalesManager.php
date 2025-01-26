<?php

namespace App\Http\Livewire\Expanse;

use App\Models\PrmConfig;
use App\Models\TrGeneralLedger;
use App\Models\TrGeneralLedgerDetails;
use App\Models\TrInvoice;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class PostSalesManager extends Component
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
    public $invPostMultiple = [];

    public function render()
    {
        $salesTax = TrInvoice::leftJoin('tr_sales', 'tr_invoices.sales_id', '=', 'tr_sales.id')
            ->leftJoin('ms_customers', 'ms_customers.id', '=', 'tr_sales.customer_id')
            ->select('tr_sales.id', 'tr_sales.number', 'tr_invoices.id as invoice_id', 'tr_invoices.number as invoice_number', 'tr_invoices.date as invoice_date', 'tr_invoices.due_termin', 'tr_invoices.due_date', 'tr_sales.date', 'tr_sales.customer_id', 'ms_customers.company_name as customer_name', 'tr_sales.reference', 'tr_invoices.total', 'tr_invoices.payment', 'tr_invoices.rest', 'tr_invoices.notes', 'tr_invoices.is_receive', 'tr_invoices.is_status', 'tr_invoices.approved_at')
            ->addSelect(DB::raw('"Tax" as type'))
            ->where('tr_invoices.is_posting', '0')
            ->whereNotNull('tr_invoices.approved_at');

        if (!empty($this->searchKeyword)) {
            $salesTax->orWhere('number', 'like', "%" . $this->searchKeyword . "%")->where('tr_invoices.is_posting', '0')->whereNotNull('tr_invoices.approved_at');
            $salesTax->orWhere('invoice_number', 'like', "%" . $this->searchKeyword . "%")->where('tr_invoices.is_posting', '0')->whereNotNull('tr_invoices.approved_at');
            $salesTax->orWhere('ms_customers.company_name', 'like', "%" . $this->searchKeyword . "%")->where('tr_invoices.is_posting', '0')->whereNotNull('tr_invoices.approved_at');
            $salesTax->orWhere('total', 'like', "%" . $this->searchKeyword . "%")->where('tr_invoices.is_posting', '0')->whereNotNull('tr_invoices.approved_at');
            $salesTax->orWhere('approved_at', 'like', "%" . $this->searchKeyword . "%")->where('tr_invoices.is_posting', '0')->whereNotNull('tr_invoices.approved_at');
        }

        $saless = $salesTax->paginate($this->perPage);

        return view('livewire.expanse.post-sales-manager', compact('saless'));
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
            $this->selected = $this->getAllSalesIds();
        } else {
            $this->selected = [];
        }
    }

    public function updatedSelected()
    {
        if (count($this->selected) === $this->getAllSalesIds()->count()) {
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

    private function getAllSalesIds()
    {
        $salesTax = TrInvoice::whereNotNull('approved_at')->where('is_posting', '0')->whereNotNull('approved_at')->pluck('id');
        return $salesTax;
    }

    public function postMultiple()
    {
        if (count($this->selected) <= 0) {
            session()->flash('error', 'Please select at least one invoices');
            $this->closeModal();
        }

        $sales = TrInvoice::whereIn('id', $this->selected)
            ->select('id', 'sales_id', 'number', 'subtotal', 'delivery_fee', 'discount', 'ppn', 'ppn_amount', 'total')
            ->addSelect(DB::raw('"Tax" as type'))
            ->get()
            ->toArray();

        $this->invPostMultiple = $sales;
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
        $this->invPostMultiple = [];

        $this->closeModal();
        $this->dispatchBrowserEvent('checkall-indeterminate-false');
    }

    public function store()
    {
        $now = Carbon::now();
        $receivableAccount = PrmConfig::where('code', 'coasr')->get()->first();
        $salesAccount = PrmConfig::where('code', 'coas')->get()->first();
        $ppnAccount = PrmConfig::where('code', 'coasp')->get()->first();

        if (!isset($receivableAccount->value) || !isset($salesAccount->value) || !isset($ppnAccount->value)) {
            session()->flash('error', 'Please set receivable account, sales account and ppn purchase account');
            $this->closeModal();
            return false;
        } else {
            if ($receivableAccount->value == '' || $salesAccount->value == '' || $ppnAccount->value == '') {
                session()->flash('error', 'Please set receivable account, sales account and ppn purchase account');
                $this->closeModal();
                return false;
            }
        }

        foreach ($this->invPostMultiple as $key => $val) {

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
                'notes' => "Sales from Inv : " . $val['number'],
                'total_debit' => $val['total'],
                'total_credit' => $val['total'],
                'created_by' => Auth::user()->id,
                'updated_by' => Auth::user()->id,
            ];
            $gl = TrGeneralLedger::create($dataGeneralLedger);

            $totalPurchase = $val['total'] - $val['ppn_amount'];

            $dataGeneralLedgerDetails = [
                'general_ledger_id' => $gl->id,
                'account_id' => $salesAccount->value,
                'type' => 'cr',
                'amount' => $totalPurchase,
                'created_by' => Auth::user()->id,
                'updated_by' => Auth::user()->id,
            ];
            TrGeneralLedgerDetails::create($dataGeneralLedgerDetails);

            $dataGeneralLedgerDetailsPPN = [
                'general_ledger_id' => $gl->id,
                'account_id' => $ppnAccount->value,
                'type' => 'cr',
                'amount' => $val['ppn_amount'],
                'created_by' => Auth::user()->id,
                'updated_by' => Auth::user()->id,
            ];
            TrGeneralLedgerDetails::create($dataGeneralLedgerDetailsPPN);

            $dataGeneralLedgerDetailsPayable = [
                'general_ledger_id' => $gl->id,
                'account_id' => $receivableAccount->value,
                'type' => 'db',
                'amount' => $val['total'],
                'created_by' => Auth::user()->id,
                'updated_by' => Auth::user()->id,
            ];
            TrGeneralLedgerDetails::create($dataGeneralLedgerDetailsPayable);

            $dataInv = [
                'is_posting' => '1',
                'updated_by' => Auth::user()->id,
            ];

            TrInvoice::find($val['id'])->update($dataInv);
        }

        $this->formReset();
        session()->flash('success', 'Saved');
    }
}
