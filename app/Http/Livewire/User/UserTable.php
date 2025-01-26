<?php

namespace App\Http\Livewire\User;

use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\User;

class UserTable extends Component
{
    use WithPagination, WithFileUploads;

    protected $paginationTheme = 'bootstrap';
    public $sortColumn = "created_at";
    public $sortOrder = "desc";
    public $sortLink = '<i class="sorticon fa-solid fa-caret-up"></i>';
    public $searchKeyword = '';
    public $roleFilter = '';
    public $set_id;

    public $name;
    public $email;
    public $password;
    public $avatar;
    public $showAvatar;
    public $role;

    public function render()
    {
        $users = User::orderby($this->sortColumn,$this->sortOrder)->select('*');
        if(!empty($this->searchKeyword)){
            $users->orWhere('name','like',"%".$this->searchKeyword."%");
            $users->orWhere('email','like',"%".$this->searchKeyword."%");
        }
        if(!empty($this->roleFilter)){
            $users->orWhere('role','=',$this->roleFilter);
        }
        $users = $users->paginate(10);

        return view('livewire.user.user-table', [ 'users' => $users ]);
    }

    public function updated()
    {
        $this->resetPage();
    }

    public function sortOrder($columnName="")
    {
        $caretOrder = "up";
        if($this->sortOrder == 'asc'){
            $this->sortOrder = 'desc';
            $caretOrder = "down";
        }else{
            $this->sortOrder = 'asc';
            $caretOrder = "up";
        }
        $this->sortLink = '<i class="sorticon fa-solid fa-caret-'.$caretOrder.'"></i>';
        $this->sortColumn = $columnName;
    }

    public function roleFilter($role)
    {
        $this->roleFilter = $role;
    }

    public function closeModal()
    {
        $this->formReset();
        $this->dispatchBrowserEvent('close-modal');
    }

    public function formReset()
    {
        $this->set_id = null;
        $this->name = null;
        $this->email = null;
        $this->password = null;
        $this->avatar = null;
        $this->role = null;
        $this->showAvatar = null;

        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function save()
    {
        if(empty($this->set_id))
        {
            $this->validate([
                'name'  => 'required|max:100',
                'email' => 'required|email|unique:users,email',
                'password' => ['required','string','min:8','regex:/[a-z]/','regex:/[A-Z]/','regex:/[0-9]/'],
                'avatar' => 'required|image|max:2048|mimes:jpg,jpeg,png,webp',
                'role'  => 'required',
            ]);

            $avatar = $this->avatar->store('/', 'avatar_disk');

            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'avatar' => $avatar,
                'role' => $this->role,
            ]);
        }
        else
        {
            $this->validate([
                'name'  => 'required|max:100',
                'email' => 'required|email|unique:users,email,'.$this->set_id,
                'password' => ['nullable','string','min:8','regex:/[a-z]/','regex:/[A-Z]/','regex:/[0-9]/'],
                'avatar' => 'nullable|image|max:2048|mimes:jpg,jpeg,png,webp',
                'role'  => 'required',
            ]);


            $user = User::find($this->set_id);

            $user->update([
                'name' => $this->name,
                'email' => $this->email,
                'role' => $this->role,
            ]);

            if( !empty($this->password) ){
                $user->password = Hash::make($this->password);
                $user->save();
            }

            if( !empty($this->avatar) ){
                $avatar = $this->avatar->store('/', 'avatar_disk');
                $user->avatar = $avatar;
                $user->save();
            }
        }

        $this->formReset();
        session()->flash('success','Saved.');
        $this->dispatchBrowserEvent('close-modal');
    }

    public function edit($id)
    {
        $user = User::find($id);
        if( $user ) {
            $this->set_id = $id;
            $this->name = $user->name;
            $this->email = $user->email;
            $this->showAvatar = $user->avatar;
            $this->role = $user->role;
        }else{
            return redirect()->to('/admin');
        }
    }

    public function delete($id)
    {
        $this->set_id = $id;
    }

    public function destroy()
    {

        User::destroy($this->set_id);
        session()->flash('success','User deleted.');
        $this->dispatchBrowserEvent('close-modal');
    }
}
