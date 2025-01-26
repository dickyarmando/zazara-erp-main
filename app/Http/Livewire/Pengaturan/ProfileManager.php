<?php

namespace App\Http\Livewire\Pengaturan;

use App\Models\MsElemenP5;
use App\Models\PrmProfile;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class ProfileManager extends Component
{
    public function index()
    {
        // $users = User::select('id','name')->with('roles:id,name')->get();
        // foreach( $users as $user ){
        //     echo 'Name : '.$user->name.'<br>';
        //     echo 'Role : ';
        //     foreach( $user->roles as $role ){
        //         echo $role->name.', ';
        //     }
        // }
        return view('admin.profile');
    }
}
