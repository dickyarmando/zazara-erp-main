<?php

namespace App\Http\Livewire\Masters;

use App\Models\MsCustomers;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CustomersCreateManager extends Component
{
    public $set_id;

    public $code;
    public $name;
    public $company_name;
    public $address;
    public $email;
    public $phone;
    public $telephone;
    public $fax;

    public function mount()
    {
        $this->set_id = request()->id;
        if (!empty($this->set_id)) {
            $customer = MsCustomers::find($this->set_id);
            $this->code = $customer->code;
            $this->name = $customer->name;
            $this->company_name = $customer->company_name;
            $this->address = $customer->address;
            $this->email = $customer->email;
            $this->phone = $customer->phone;
            $this->telephone = $customer->telephone;
            $this->fax = $customer->fax;
        }
    }

    public function render()
    {
        return view('livewire.masters.customers-create-manager');
    }

    public function backRedirect()
    {
        return redirect()->to('/masters/customers');
    }

    public function store()
    {
        $rules = [
            'code' => 'required',
            'name' => 'required',
            'company_name' => 'required',
            'address' => '',
            'email' => '',
            'phone' => '',
            'telephone' => '',
            'fax' => ''
        ];

        if (empty($this->set_id)) {

            $countCustomers = MsCustomers::where('code', $this->code)
                ->where('is_status', '1')
                ->orWhere('company_name', $this->company_name)
                ->where('is_status', '1')
                ->count();

            if ($countCustomers > 0) {
                session()->flash('error', 'Failed, Code or Company Name already exist..');
                return false;
            }

            $valid = $this->validate($rules);
            $valid['created_by'] = Auth::user()->id;
            $valid['updated_by'] = Auth::user()->id;
            MsCustomers::create($valid);
        } else {

            $countCustomers = MsCustomers::where('code', $this->code)
                ->where('is_status', '1')
                ->where('id', '!=', $this->set_id)
                ->orWhere('company_name', $this->company_name)
                ->where('is_status', '1')
                ->where('id', '!=', $this->set_id)
                ->count();

            if ($countCustomers > 0) {
                session()->flash('error', 'Failed, Code or Company Name already exist..');
                return false;
            }

            $valid = $this->validate($rules);
            $valid['updated_by'] = Auth::user()->id;
            $customer = MsCustomers::find($this->set_id);
            $customer->update($valid);
        }

        session()->flash('success', 'Saved');
        return redirect()->to('/masters/customers');
    }
}
