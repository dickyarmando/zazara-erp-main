<?php

namespace App\Http\Livewire\Payment;

use App\Models\MsCustomers;
use App\Models\PrmCompanies;
use App\Models\PrmConfig;
use App\Models\TrInvoice;
use App\Models\TrInvoicesNon;
use App\Models\TrReceives;
use App\Models\TrSales;
use App\Models\TrSalesDetails;
use App\Models\TrSalesNon;
use App\Models\TrSalesNonDetails;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ReceivePaymentViewManager extends Component
{
    public $set_id;
    public $set_type;

    public $date;
    public $amount;
    public $payment_method_id;
    public $notes;

    public $sales;
    public $items;

    public $invoices;

    public function mount()
    {
        $this->set_id = request()->id;
        $this->set_type = request()->type;
        $invoices = TrInvoice::find($this->set_id);
        if ($this->set_type === 'Non') {
            $invoices = TrInvoicesNon::find($this->set_id);
        }
        $this->invoices = $invoices;

        $this->formReset();
    }

    public function render()
    {
        $salesType = '1';
        if ($this->set_type == 'Non') {
            $salesType = '2';
        }

        $companies = PrmCompanies::find(1);
        $customers = MsCustomers::find($this->sales->customer_id);
        $invSignName = PrmConfig::find(5);
        $invSignPosition = PrmConfig::find(6);
        $invTC = PrmConfig::find(7);
        $receives = TrReceives::where('sales_id', $this->set_id)
            ->where('sales_type', $salesType)
            ->where('is_status', '1')
            ->orderBy('id', 'asc')->get();

        return view('livewire.payment.receive-payment-view-manager', compact('companies', 'customers', 'receives', 'invSignName', 'invSignPosition', 'invTC'));
    }

    public function closeModal()
    {
        $this->resetErrorBag();
        $this->resetValidation();
        $this->dispatchBrowserEvent('close-modal');
    }

    public function backRedirect()
    {
        return redirect()->to('/receive');
    }

    public function print()
    {
        if ($this->set_type == 'Tax') {
            $this->dispatchBrowserEvent('print-tax');
        } else if ($this->set_type == 'Non') {
            $this->dispatchBrowserEvent('print-non');
        }
    }

    public function formReset()
    {
        $now = Carbon::now();
        $this->date = $now->format('Y-m-d');

        if ($this->set_type == 'Tax') {
            $this->sales = TrSales::find($this->invoices->sales_id);
            $this->items = TrSalesDetails::where('sales_id', $this->sales->id)
                ->select('id', 'product_name as name', 'unit_name as unit', 'qty', 'rate as price', 'amount as total')
                ->get()->toArray();
        } else if ($this->set_type == 'Non') {
            $this->sales = TrSalesNon::find($this->invoices->sales_non_id);
            $this->items = TrSalesNonDetails::where('sales_non_id', $this->sales->id)
                ->select('id', 'product_name as name', 'unit_name as unit', 'qty', 'rate as price', 'amount as total')
                ->get()->toArray();
        }

        $this->amount = $this->sales->rest;
        $this->payment_method_id = 1;
        $this->notes = null;

        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function store()
    {
        $rules = [
            'amount' => 'required',
            'date' => 'required',
            'payment_method_id' => 'required',
            'notes' => '',
        ];
        $valid = $this->validate($rules);

        $salesType = '1';
        if ($this->set_type == 'Non') {
            $salesType = '2';
        }

        $valid['sales_id'] = $this->set_id;
        $valid['sales_type'] = $salesType;
        $valid['created_by'] = Auth::user()->id;
        $valid['updated_by'] = Auth::user()->id;
        TrReceives::create($valid);

        $receive = $this->sales->payment + $this->amount;
        $balance = $this->sales->total - $receive;

        $dataSales = [
            'payment' => $receive,
            'rest' => $balance,
            'is_payed' => '0',
            'updated_by' => Auth::user()->id,
        ];

        if ($balance <= 0) {
            $dataSales['is_receive'] = '1';
        }

        if ($this->set_type == 'Tax') {
            $invoices = TrInvoice::find($this->set_id);
            $invoices->update($dataSales);
            TrSales::find($invoices->sales_id)->update($dataSales);
        } else if ($this->set_type == 'Non') {
            $invoices = TrInvoicesNon::find($this->set_id);
            $invoices->update($dataSales);
            TrSalesNon::find($invoices->sales_non_id)->update($dataSales);
        }

        $this->formReset();
        session()->flash('success', 'Saved');
        $this->dispatchBrowserEvent('close-modal');
    }
}
