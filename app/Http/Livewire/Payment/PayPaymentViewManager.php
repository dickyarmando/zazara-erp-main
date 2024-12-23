<?php

namespace App\Http\Livewire\Payment;

use App\Models\MsPaymentMethods;
use App\Models\MsSuppliers;
use App\Models\PrmCompanies;
use App\Models\PrmConfig;
use App\Models\TrPayments;
use App\Models\TrPurchase;
use App\Models\TrPurchaseDetails;
use App\Models\TrPurchaseNon;
use App\Models\TrPurchaseNonDetails;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class PayPaymentViewManager extends Component
{
    public $set_id;
    public $set_type;

    public $date;
    public $amount;
    public $payment_method_id;
    public $notes;

    public $purchase;
    public $items;

    public function mount()
    {
        $this->set_id = request()->id;
        $this->set_type = request()->type;

        $this->formReset();
    }

    public function render()
    {
        $purchaseType = '1';
        if ($this->set_type == 'Non') {
            $purchaseType = '2';
        }

        $companies = PrmCompanies::find(1);
        $suppliers = MsSuppliers::find($this->purchase->supplier_id);
        $payments = TrPayments::where('purchase_id', $this->set_id)
            ->where('purchase_type', $purchaseType)
            ->where('is_status', '1')
            ->orderBy('id', 'asc')->get();
        $poSign = PrmConfig::find(2);

        return view('livewire.payment.pay-payment-view-manager', compact('companies', 'suppliers', 'payments', 'poSign'));
    }

    public function closeModal()
    {
        $this->resetErrorBag();
        $this->resetValidation();
        $this->dispatchBrowserEvent('close-modal');
    }

    public function backRedirect()
    {
        return redirect()->to('/pay');
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
            $this->purchase = TrPurchase::find($this->set_id);
            $this->items = TrPurchaseDetails::where('purchase_id', $this->purchase->id)
                ->select('id', 'product_name as name', 'unit_name as unit', 'qty', 'rate as price', 'amount as total')
                ->get()->toArray();
        } else if ($this->set_type == 'Non') {
            $this->purchase = TrPurchaseNon::find($this->set_id);
            $this->items = TrPurchaseNonDetails::where('purchase_non_id', $this->purchase->id)
                ->select('id', 'product_name as name', 'unit_name as unit', 'qty', 'rate as price', 'amount as total')
                ->get()->toArray();
        }

        $this->amount = $this->purchase->rest;
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

        $purchaseType = '1';
        if ($this->set_type == 'Non') {
            $purchaseType = '2';
        }

        $valid['purchase_id'] = $this->set_id;
        $valid['purchase_type'] = $purchaseType;
        $valid['created_by'] = Auth::user()->id;
        $valid['updated_by'] = Auth::user()->id;
        TrPayments::create($valid);

        $payment = $this->purchase->payment + $this->amount;
        $balance = $this->purchase->total - $payment;

        $dataPurchase = [
            'payment' => $payment,
            'rest' => $balance,
            'is_payed' => '0',
            'updated_by' => Auth::user()->id,
        ];

        if ($balance <= 0) {
            $dataPurchase['is_payed'] = '1';
        }

        if ($this->set_type == 'Tax') {
            TrPurchase::find($this->set_id)->update($dataPurchase);
        } else if ($this->set_type == 'Non') {
            TrPurchaseNon::find($this->set_id)->update($dataPurchase);
        }

        $this->formReset();
        session()->flash('success', 'Saved');
        $this->dispatchBrowserEvent('close-modal');
    }
}
