<?php

namespace App\Http\Livewire\Masters;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class UserCreateManager extends Component
{
    public $set_id;
    public $username;
    public $name;
    public $email;
    public $phone;
    public $role_id;

    public function mount()
    {
        $this->set_id = request()->id;
        if (!empty($this->set_id)) {
            $user = User::find($this->set_id);
            $this->username = $user->username;
            $this->name = $user->name;
            $this->email = $user->email;
            $this->phone = $user->phone;
            $this->role_id = $user->role_id;
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
            User::create($valid);
        } else {
            $valid = $this->validate($rules);
            $valid['updated_by'] = Auth::user()->id;
            $users = User::find($this->set_id);
            $users->update($valid);
        }

        session()->flash('success', 'Success');
        return redirect()->to('/masters/users');
    }
}
