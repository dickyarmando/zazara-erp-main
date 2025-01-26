<?php

namespace App\Http\Livewire\Masters;

use App\Models\PrmCompanies;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProfileCompanyManager extends Component
{
    use WithFileUploads;

    public $company_id;
    public $name;
    public $address;
    public $phone;
    public $telephone;
    public $fax;
    public $email;
    public $website;
    public $picture;

    public function mount()
    {
        $prmCompanies = PrmCompanies::find(1);

        $this->company_id = $prmCompanies->id;
        $this->name = $prmCompanies->name;
        $this->address = $prmCompanies->address;
        $this->phone = $prmCompanies->phone;
        $this->telephone = $prmCompanies->telephone;
        $this->fax = $prmCompanies->fax;
        $this->email = $prmCompanies->email;
        $this->website = $prmCompanies->website;
        $this->picture = $prmCompanies->picture;
    }

    public function render()
    {
        return view('livewire.masters.profile-company-manager');
    }

    public function store()
    {
        $rules = [
            'name' => 'required',
            'email' => 'email',
            'phone' => '',
            'telephone' => '',
            'fax' => '',
            'website' => '',
            'address' => '',
        ];

        $valid = $this->validate($rules);
        $valid['updated_by'] = Auth::user()->id;
        $pc = PrmCompanies::find(1);
        $pc->update($valid);

        session()->flash('success', 'Success');
        return redirect()->to('/masters/profile-company');
    }
}
