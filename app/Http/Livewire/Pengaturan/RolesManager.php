<?php

namespace App\Http\Livewire\Pengaturan;

use App\Models\PrmRoleMenu;
use App\Models\PrmRoles;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class RolesManager extends Component
{
    use WithPagination, WithFileUploads;

    protected $paginationTheme = 'bootstrap';
    public $perPage = 10;
    public $sortColumn = "prm_roles.name";
    public $sortOrder = "asc";
    public $sortLink = '<i class="sorticon fa-solid fa-caret-up"></i>';
    public $searchKeyword = '';
    public $roleFilter = '';
    public $set_id;

    public $name;
    public $menus = [];

    public function render()
    {
        $role = PrmRoles::orderby($this->sortColumn, $this->sortOrder)
            ->select('prm_roles.id', 'prm_roles.name', 'prm_roles.is_active');
        if (!empty($this->searchKeyword)) {
            $role->orWhere('prm_roles.name', 'like', "%" . $this->searchKeyword . "%")->where('is_active', '1');
        }
        $roles = $role->where('is_active', '1')->paginate($this->perPage);

        return view('livewire.pengaturan.roles-manager', ['roles' => $roles]);
    }

    public function updated()
    {
        $this->resetPage();
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

    public function closeModal()
    {
        $this->formReset();
        $this->resetErrorBag();
        $this->resetValidation();
        $this->dispatchBrowserEvent('close-modal');
    }

    public function formReset()
    {
        $this->set_id = null;
        $this->name = null;
        $this->menus = null;

        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function store()
    {
        $roles = [
            'name' => [
                'required',
                Rule::unique('prm_roles')->where(function ($query) {
                    return $query->where('name', $this->name)
                        ->where('is_active', '1');
                }),
            ],
        ];

        if (empty($this->set_id)) {
            $valid = $this->validate($roles);
            $roles = PrmRoles::create($valid);

            PrmRoleMenu::where('role_id', $roles->id)->delete();
            foreach ($this->menus as $km => $vm) {
                $rolesMenu = [
                    "role_id" => $roles->id,
                    "menu_id" => $vm,
                ];
                PrmRoleMenu::create($rolesMenu);
            }
        } else {
            PrmRoleMenu::where('role_id', $this->set_id)->delete();
            foreach ($this->menus as $km => $vm) {
                $rolesMenu = [
                    "role_id" => $this->set_id,
                    "menu_id" => $vm,
                ];
                PrmRoleMenu::create($rolesMenu);
            }
        }

        $this->formReset();
        session()->flash('success', 'Saved.');
        $this->dispatchBrowserEvent('close-modal');
    }

    public function edit($id)
    {
        $prmRole = PrmRoles::find($id);
        $prmRoleMenu = PrmRoleMenu::where('role_id', $id)->where('is_active', '1')->pluck('menu_id', 'menu_id')->toArray();
        $this->set_id = $id;
        $this->name = $prmRole->name;
        $this->menus = $prmRoleMenu;
    }
}
