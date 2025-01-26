<?php

namespace App\Http\Livewire\Masters;

use App\Models\MsSuppliers;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class SuppliersCreateManager extends Component
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
        $now = Carbon::now();

        $this->set_id = request()->id;
        if (!empty($this->set_id)) {
            $supplier = MsSuppliers::find($this->set_id);
            $this->code = $supplier->code;
            $this->name = $supplier->name;
            $this->company_name = $supplier->company_name;
            $this->address = $supplier->address;
            $this->email = $supplier->email;
            $this->phone = $supplier->phone;
            $this->telephone = $supplier->telephone;
            $this->fax = $supplier->fax;
        } else {
            $this->code = 'SPL' . $now->year . $now->month . $now->day . $now->hour . $now->minute . $now->second;
        }
    }

    public function render()
    {
        return view('livewire.masters.suppliers-create-manager');
    }

    public function backRedirect()
    {
        return redirect()->to('/masters/suppliers');
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

            $countSuppliers = MsSuppliers::where('code', $this->code)
                ->where('is_status', '1')
                ->orWhere('company_name', $this->company_name)
                ->where('is_status', '1')
                ->count();

            if ($countSuppliers > 0) {
                session()->flash('error', 'Failed, Code or Company Name already exist..');
                return false;
            }

            $valid = $this->validate($rules);
            $valid['created_by'] = Auth::user()->id;
            $valid['updated_by'] = Auth::user()->id;
            MsSuppliers::create($valid);
        } else {

            $countSuppliers = MsSuppliers::where('code', $this->code)
                ->where('is_status', '1')
                ->where('id', '!=', $this->set_id)
                ->orWhere('company_name', $this->company_name)
                ->where('is_status', '1')
                ->where('id', '!=', $this->set_id)
                ->count();

            if ($countSuppliers > 0) {
                session()->flash('error', 'Failed, Code or Company Name already exist..');
                return false;
            }

            $valid = $this->validate($rules);
            $valid['updated_by'] = Auth::user()->id;
            $supplier = MsSuppliers::find($this->set_id);
            $supplier->update($valid);
        }

        session()->flash('success', 'Saved');
        return redirect()->to('/masters/suppliers');
    }
}
