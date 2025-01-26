<?php

namespace App\Http\Livewire\Masters;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class UserManager extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $perPage = 10;
    public $sortColumn = "users.name";
    public $sortOrder = "asc";
    public $sortLink = '<i class="sorticon fa-solid fa-caret-up"></i>';
    public $searchKeyword = '';
    public $roleFilter = '';
    public $set_id;

    public function render()
    {
        $queryUsers = User::orderBy($this->sortColumn, $this->sortOrder)
            ->leftJoin('prm_roles', 'prm_roles.id', '=', 'users.role_id')
            ->select('users.id', 'users.name', 'users.username', 'users.email', 'users.phone', 'users.role_id', 'prm_roles.name as role_name');

        if (!empty($this->searchKeyword)) {
            $queryUsers->orWhere('users.name', 'like', "%" . $this->searchKeyword . "%")->where('users.is_status', '1');
            $queryUsers->orWhere('users.username', 'like', "%" . $this->searchKeyword . "%")->where('users.is_status', '1');
            $queryUsers->orWhere('users.email', 'like', "%" . $this->searchKeyword . "%")->where('users.is_status', '1');
            $queryUsers->orWhere('users.phone', 'like', "%" . $this->searchKeyword . "%")->where('users.is_status', '1');
            $queryUsers->orWhere('prm_roles.name', 'like', "%" . $this->searchKeyword . "%")->where('users.is_status', '1');
        }

        $users = $queryUsers->where('users.is_status', '1')->paginate($this->perPage);

        return view('livewire.masters.user-manager', ['users' => $users]);
    }

    public function sortOrder($columnName = "")
    {
        $caretOrder = "up";
        if ($this->sortOrder == 'asc') {
            $this->sortOrder = 'desc';
            $caretOrder = "down";
        } else {
            $this->sortOrder = 'asc';
            $caretOrder = "up";
        }
        $this->sortLink = '<i class="sorticon fa-solid fa-caret-' . $caretOrder . '"></i>';
        $this->sortColumn = $columnName;
    }

    public function edit($id)
    {
        return redirect()->to('/masters/users/create?id=' . $id);
    }

    public function delete($id)
    {
        $this->set_id = $id;
    }

    public function destroy()
    {
        $valid = [
            'is_status' => '0',
            'deleted_at' => Carbon::now()->toDateTimeString(),
            'deleted_by' => Auth::user()->id
        ];

        $tp = User::find($this->set_id);
        $tp->update($valid);

        $this->formReset();
        session()->flash('success', 'Deleted');
        $this->dispatchBrowserEvent('close-modal');
    }

    public function formReset()
    {
        $this->set_id = null;

        $this->resetErrorBag();
        $this->resetValidation();
    }
}
