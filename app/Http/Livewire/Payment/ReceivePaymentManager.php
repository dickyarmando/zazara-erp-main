<?php

namespace App\Http\Livewire\Payment;

use App\Models\PrmConfig;
use App\Models\TrInvoice;
use App\Models\TrInvoicesNon;
use App\Models\TrReceives;
use App\Models\TrSales;
use App\Models\TrSalesNon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class ReceivePaymentManager extends Component
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
    public $selectedN = [];
    public $selectAll = false;
    public $invReceiveMultiple = [];

    public function render()
    {
        $salesTax = TrInvoice::leftJoin('tr_sales', 'tr_invoices.sales_id', '=', 'tr_sales.id')
            ->leftJoin('ms_customers', 'ms_customers.id', '=', 'tr_sales.customer_id')
            ->select('tr_sales.id', 'tr_sales.number', 'tr_invoices.id as invoice_id', 'tr_invoices.number as invoice_number', 'tr_invoices.date as invoice_date', 'tr_invoices.due_termin', 'tr_invoices.due_date', 'tr_sales.date', 'tr_sales.customer_id', 'ms_customers.company_name as customer_name', 'tr_sales.reference', 'tr_invoices.total', 'tr_invoices.payment', 'tr_invoices.rest', 'tr_invoices.notes', 'tr_invoices.is_receive', 'tr_invoices.is_status')
            ->selectRaw('datediff(tr_invoices.due_date, now()) as date_diff')
            ->addSelect(DB::raw('"Tax" as type'))
            ->where('tr_invoices.is_receive', '0')
            ->where('tr_invoices.approved_at', '!=', null);

        $salesNonTax = TrInvoicesNon::leftJoin('tr_sales_non', 'tr_invoices_nons.sales_non_id', '=', 'tr_sales_non.id')
            ->leftJoin('ms_customers', 'ms_customers.id', '=', 'tr_sales_non.customer_id')
            ->select('tr_sales_non.id', 'tr_sales_non.number', 'tr_invoices_nons.id as invoice_id', 'tr_invoices_nons.number as invoice_number', 'tr_invoices_nons.date as invoice_date', 'tr_invoices_nons.due_termin', 'tr_invoices_nons.due_date', 'tr_sales_non.date', 'tr_sales_non.customer_id', 'ms_customers.company_name as customer_name', 'tr_sales_non.reference', 'tr_invoices_nons.total', 'tr_invoices_nons.payment', 'tr_invoices_nons.rest', 'tr_invoices_nons.notes', 'tr_invoices_nons.is_receive', 'tr_invoices_nons.is_status')
            ->selectRaw('datediff(tr_invoices_nons.due_date, now()) as date_diff')
            ->addSelect(DB::raw('"Non" as type'))
            ->where('tr_invoices_nons.is_receive', '0')
            ->where('tr_invoices_nons.approved_at', '!=', null);

        if (!empty($this->searchKeyword)) {
            $salesTax->orWhere('number', 'like', "%" . $this->searchKeyword . "%")->where('tr_invoices.is_receive', '0')->where('tr_invoices.approved_at', '!=', null);
            $salesTax->orWhere('invoice_number', 'like', "%" . $this->searchKeyword . "%")->where('tr_invoices.is_receive', '0')->where('tr_invoices.approved_at', '!=', null);
            $salesTax->orWhere('ms_customers.company_name', 'like', "%" . $this->searchKeyword . "%")->where('tr_invoices.is_receive', '0')->where('tr_invoices.approved_at', '!=', null);
            $salesTax->orWhere('total', 'like', "%" . $this->searchKeyword . "%")->where('tr_invoices.is_receive', '0')->where('tr_invoices.approved_at', '!=', null);
            $salesTax->orWhere('payment', 'like', "%" . $this->searchKeyword . "%")->where('tr_invoices.is_receive', '0')->where('tr_invoices.approved_at', '!=', null);
            $salesTax->orWhere('rest', 'like', "%" . $this->searchKeyword . "%")->where('tr_invoices.is_receive', '0')->where('tr_invoices.approved_at', '!=', null);

            $salesNonTax->orWhere('number', 'like', "%" . $this->searchKeyword . "%")->where('tr_invoices_nons.is_receive', '0')->where('tr_invoices_nons.approved_at', '!=', null);
            $salesNonTax->orWhere('invoice_number', 'like', "%" . $this->searchKeyword . "%")->where('tr_invoices_nons.is_receive', '0')->where('tr_invoices_nons.approved_at', '!=', null);
            $salesNonTax->orWhere('ms_customers.company_name', 'like', "%" . $this->searchKeyword . "%")->where('tr_invoices_nons.is_receive', '0')->where('tr_invoices_nons.approved_at', '!=', null);
            $salesNonTax->orWhere('total', 'like', "%" . $this->searchKeyword . "%")->where('tr_invoices_nons.is_receive', '0')->where('tr_invoices_nons.approved_at', '!=', null);
            $salesNonTax->orWhere('payment', 'like', "%" . $this->searchKeyword . "%")->where('tr_invoices_nons.is_receive', '0')->where('tr_invoices_nons.approved_at', '!=', null);
            $salesNonTax->orWhere('rest', 'like', "%" . $this->searchKeyword . "%")->where('tr_invoices_nons.is_receive', '0')->where('tr_invoices_nons.approved_at', '!=', null);
        }

        $saless = $salesTax->union($salesNonTax)->orderBy($this->sortColumn, $this->sortOrder);
        $saless = $saless->paginate($this->perPage);

        $invoiceTerminColor = PrmConfig::where('is_status', '1')->whereIn('code', ['itd', 'itw'])->get()->keyBy('code');;

        return view('livewire.payment.receive-payment-manager', compact('saless', 'invoiceTerminColor'));
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

    public function view($id, $type)
    {
        return redirect()->to('/receive/view/' . $id . '/' . $type);
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selected = $this->getAllSalesIds();
            $this->selectedN = $this->getAllSalesIdsN();
        } else {
            $this->selected = [];
            $this->selectedN = [];
        }
    }

    public function updatedSelected()
    {
        if (count($this->selected) + count($this->selectedN) === $this->getAllSalesIds()->count() + $this->getAllSalesIdsN()->count()) {
            $this->selectAll = true;
            $this->dispatchBrowserEvent('checkall-indeterminate-false');
            $this->dispatchBrowserEvent('checkall-checked');
        } elseif (count($this->selected) + count($this->selectedN) === 0) {
            $this->selectAll = false;
            $this->dispatchBrowserEvent('checkall-checked-false');
            $this->dispatchBrowserEvent('checkall-indeterminate-false');
        } else {
            $this->dispatchBrowserEvent('checkall-indeterminate');
        }
    }

    public function updatedSelectedN()
    {
        if (count($this->selected) + count($this->selectedN) === $this->getAllSalesIds()->count() + $this->getAllSalesIdsN()->count()) {
            $this->selectAll = true;
            $this->dispatchBrowserEvent('checkall-indeterminate-false');
            $this->dispatchBrowserEvent('checkall-checked');
        } elseif (count($this->selected) + count($this->selectedN) === 0) {
            $this->selectAll = false;
            $this->dispatchBrowserEvent('checkall-indeterminate-false');
            $this->dispatchBrowserEvent('checkall-checked-false');
        } else {
            $this->dispatchBrowserEvent('checkall-indeterminate');
        }
    }

    private function getAllSalesIds()
    {
        $salesTax = TrInvoice::whereNotNull('approved_at')->where('is_receive', '0')->where('approved_at', '!=', null)->pluck('id');
        return $salesTax;
    }

    private function getAllSalesIdsN()
    {
        $salesNonTax = TrInvoicesNon::whereNotNull('approved_at')->where('is_receive', '0')->where('approved_at', '!=', null)->pluck('id');
        return $salesNonTax;
    }

    public function receiveMultiple()
    {
        if (count($this->selected) <= 0 && count($this->selectedN) <= 0) {
            session()->flash('error', 'Please select at least one invoices');
            $this->closeModal();
        }

        $sales = TrInvoice::whereIn('id', $this->selected)
            ->select('id', 'sales_id', 'number', 'total', 'payment', 'rest', 'rest as amount')
            ->addSelect(DB::raw('"Tax" as type'))
            ->addSelect(DB::raw('CURDATE() as date'))
            ->addSelect(DB::raw('"1" as payment_method_id'))
            ->addSelect(DB::raw('"" as notes'))
            ->get()
            ->toArray();
        $salesN = TrInvoicesNon::whereIn('id', $this->selectedN)
            ->select('id', 'sales_non_id as sales_id', 'number', 'total', 'payment', 'rest', 'rest as amount')
            ->addSelect(DB::raw('"Non" as type'))
            ->addSelect(DB::raw('CURDATE() as date'))
            ->addSelect(DB::raw('"1" as payment_method_id'))
            ->addSelect(DB::raw('"" as notes'))
            ->get()
            ->toArray();

        $this->invReceiveMultiple = array_merge($sales, $salesN);
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
        $this->selectedN = [];
        $this->selectAll = false;
        $this->invReceiveMultiple = [];

        $this->closeModal();
        $this->dispatchBrowserEvent('checkall-indeterminate-false');
    }

    public function store()
    {
        foreach ($this->invReceiveMultiple as $key => $val) {

            $salesType = '1';
            if ($val['type'] == 'Non') {
                $salesType = '2';
            }

            $dataReceive = [
                'sales_id' => $val['sales_id'],
                'sales_type' => $salesType,
                'date' => $val['date'],
                'payment_method_id' => $val['payment_method_id'],
                'amount' => $val['amount'],
                'notes' => $val['notes'],
                'created_by' => Auth::user()->id,
                'updated_by' => Auth::user()->id,
            ];

            TrReceives::create($dataReceive);

            $receive = $val['payment'] + $val['amount'];
            $balance = $val['total'] - $receive;

            $dataSales = [
                'payment' => $receive,
                'rest' => $balance,
                'is_payed' => '0',
                'updated_by' => Auth::user()->id,
            ];

            if ($balance <= 0) {
                $dataSales['is_receive'] = '1';
            }

            if ($val['type'] == 'Tax') {
                $invoices = TrInvoice::find($val['id']);
                $invoices->update($dataSales);
                TrSales::find($invoices->sales_id)->update($dataSales);
            } else if ($val['type'] == 'Non') {
                $invoices = TrInvoicesNon::find($val['id']);
                $invoices->update($dataSales);
                TrSalesNon::find($invoices->sales_non_id)->update($dataSales);
            }
        }

        $this->formReset();
        session()->flash('success', 'Saved');
    }
}
