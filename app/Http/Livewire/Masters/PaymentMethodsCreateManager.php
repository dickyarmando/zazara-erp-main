<?php

namespace App\Http\Livewire\Masters;

use App\Models\MsPaymentMethods;
use Livewire\Component;

class PaymentMethodsCreateManager extends Component
{
    public $set_id;

    public $name;

    public function mount()
    {
        $this->set_id = request()->id;
        if (!empty($this->set_id)) {
            $paymentMethods = MsPaymentMethods::find($this->set_id);
            $this->name = $paymentMethods->name;
        }
    }

    public function render()
    {
        return view('livewire.masters.payment-methods-create-manager');
    }

    public function backRedirect()
    {
        return redirect()->to('/masters/payment-methods');
    }

    public function store()
    {
        $rules = [
            'name' => 'required',
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
}
