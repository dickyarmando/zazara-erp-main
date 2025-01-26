<?php

namespace App\Http\Livewire\Masters;

use App\Models\MsInsentifSales;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class UserCreateManager extends Component
{
    public $set_id;
    public $set_id_insentif;

    public $username;
    public $name;
    public $email;
    public $phone;
    public $role_id;
    public $target_amount;
    public $up;
    public $down;

    public function mount()
    {
        $this->target_amount = 0;
        $this->up = 0;
        $this->down = 0;

        $this->set_id = request()->id;
        if (!empty($this->set_id)) {
            $user = User::find($this->set_id);
            $this->username = $user->username;
            $this->name = $user->name;
            $this->email = $user->email;
            $this->phone = $user->phone;
            $this->role_id = $user->role_id;

            $userInsentif = MsInsentifSales::where('user_id', $this->set_id)->get()->first();
            if (isset($userInsentif->id)) {
                $this->set_id_insentif = $userInsentif->id;
                $this->target_amount = $userInsentif->target_amount;
                $this->up = $userInsentif->up;
                $this->down = $userInsentif->down;
            }
        }
    }

    public function render()
    {
        return view('livewire.masters.user-create-manager');
    }

    public function backRedirect()
    {
        return redirect()->to('/masters/users');
    }

    public function store()
    {
        $rules = [
            'username' => 'required',
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'role_id' => 'required',
        ];

        if (empty($this->set_id)) {
            $countUsername = User::where('username', $this->username)
                ->where('is_status', '1')
                ->count();

            if ($countUsername > 0) {
                session()->flash('error', 'Failed, Username already exist..');
                return false;
            }

            $valid = $this->validate($rules);
            $valid['password'] = Hash::make($this->username);
            $valid['created_by'] = Auth::user()->id;
            $valid['updated_by'] = Auth::user()->id;
            $user = User::create($valid);

            $rulesInsentif = [
                'target_amount' => '',
                'up' => 'numeric',
                'down' => 'numeric',
            ];
            $validInsentif = $this->validate($rulesInsentif);
            $validInsentif['user_id'] = $user->id;
            $validInsentif['created_by'] = Auth::user()->id;
            $validInsentif['updated_by'] = Auth::user()->id;
            MsInsentifSales::create($validInsentif);
        } else {
            $valid = $this->validate($rules);
            $valid['updated_by'] = Auth::user()->id;
            $users = User::find($this->set_id);
            $users->update($valid);

            $rulesInsentif = [
                'target_amount' => '',
                'up' => 'numeric',
                'down' => 'numeric',
            ];

            if (empty($this->set_id_insentif)) {
                $validInsentif = $this->validate($rulesInsentif);
                $validInsentif['user_id'] = $this->set_id;
                $validInsentif['created_by'] = Auth::user()->id;
                $validInsentif['updated_by'] = Auth::user()->id;
                MsInsentifSales::create($validInsentif);
            } else {
                $validInsentif = $this->validate($rulesInsentif);
                $validInsentif['updated_by'] = Auth::user()->id;
                $usersInsentif = MsInsentifSales::find($this->set_id_insentif);
                $usersInsentif->update($validInsentif);
            }
        }

        session()->flash('success', 'Success');
        return redirect()->to('/masters/users');
    }
}
