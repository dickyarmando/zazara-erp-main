<?php

namespace App\Http\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ChangePassword extends Component
{
    public $current_password;
    public $new_password;
    public $new_password_confirmation;

    public function render()
    {
        return view('livewire.auth.change-password');
    }

    public function store()
    {
        $this->validate([
            'current_password' => ['required', function ($attribute, $value, $fail) {
                if (!Hash::check($value, Auth::user()->password)) {
                    return $fail(__('The current password is incorrect.'));
                }
            }],
            'new_password'  => ['required', 'confirmed', 'string', 'min:8', 'max:100'],
            'new_password_confirmation'  => 'required'
        ]);

        Auth()->user()->update(['password' => $this->new_password]);

        auth()->logout();

        request()->session()->regenerate();
        request()->session()->regenerateToken();

        return redirect()->to('/login')->with('success', 'Password has been updated successfully');
    }
}
