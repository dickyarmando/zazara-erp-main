<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\User;

class LivewireController extends Controller
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
        return view('admin.component');
    }
}
