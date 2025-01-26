<?php

namespace App\Http\Livewire\Auth;

use App\Models\PrmProfile;
use Livewire\Component;

class ChangeProfile extends Component
{
    public $name;
    public $address;

    public function mount()
    {
        // $prmProfile = PrmProfile::where('id', '1')
        //     ->get()->first();
        // $this->name = $prmProfile->name;
        // $this->address = $prmProfile->address;
    }

    public function render()
    {
        return view('livewire.auth.change-profile');
    }

    public function store()
    {
        $this->validate([
            'name'  => 'required|string|max:100',
            'address' => 'required',
        ]);

        PrmProfile::where('id', '1')->update([
            'name' => $this->name,
            'address' => $this->address,
        ]);

        session()->flash('success', 'Profil Sekolah Berhasil Di Ubah..');
    }
}
