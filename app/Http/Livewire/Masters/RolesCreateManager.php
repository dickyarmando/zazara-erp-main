<?php

namespace App\Http\Livewire\Masters;

use App\Models\PrmMenus;
use App\Models\PrmRoleMenus;
use App\Models\PrmRoles;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;

class RolesCreateManager extends Component
{
    public $set_id;

    public $name;
    public $menus = [];

    public function mount()
    {
        $this->set_id = $roleId = request()->id;

        if (!empty($this->set_id)) {
            $roles = PrmRoles::find($this->set_id);
            $this->name = $roles->name;
        }

        $menus = PrmMenus::leftJoin('prm_role_menus', function ($join) use ($roleId) {
            $join->on('prm_role_menus.menu_id', '=', 'prm_menus.id')
                ->where('prm_role_menus.role_id', '=', $roleId)
                ->where('prm_role_menus.is_status', '=', '1');
        })
            ->select(
                'prm_menus.id',
                'prm_menus.name',
                'prm_menus.seq',
                'prm_menus.is_show',
                'prm_menus.is_create',
                'prm_menus.is_update',
                'prm_menus.is_delete',
                'prm_role_menus.is_show as show',
                DB::raw("CASE WHEN prm_role_menus.is_create = '1' THEN '1' ELSE NULL END as `create`"),
                DB::raw("CASE WHEN prm_role_menus.is_update = '1' THEN '1' ELSE NULL END as `update`"),
                DB::raw("CASE WHEN prm_role_menus.is_delete = '1' THEN '1' ELSE NULL END as `delete`"),
                'prm_menus.parent_id'
            )
            ->where('prm_menus.is_show', '=', '1')
            ->where('prm_menus.is_status', '=', '1')
            ->where('prm_menus.id', '!=', '1')
            ->orderBy('prm_menus.seq', 'asc')
            ->get()
            ->groupBy('parent_id')
            ->toArray();

        $parentMenus = $menus[0] ?? [];

        foreach ($parentMenus as $key => $menu) {
            $subMenus = $menus[$menu['id']] ?? [];

            $parentMenus[$key]['children'] = $subMenus;
        }

        $this->menus = $parentMenus;
    }

    public function render()
    {
        return view('livewire.masters.roles-create-manager');
    }

    public function store()
    {
        $roles = [
            'name' => [
                'required',
                Rule::unique('prm_roles')->where(function ($query) {
                    return $query->where('name', $this->name)
                        ->where('is_status', '1');
                }),
            ],
        ];

        if (empty($this->set_id)) {
            $valid = $this->validate($roles);
            $roles = PrmRoles::create($valid);

            $this->set_id = $roles->id;
            PrmRoleMenus::where('role_id', $roles->id)->delete();
        } else {
            PrmRoleMenus::where('role_id', $this->set_id)->delete();
        }

        $rolesMenu = [
            "role_id" => $this->set_id,
            "menu_id" => '1',
        ];

        PrmRoleMenus::create($rolesMenu);

        foreach ($this->menus as $km => $vm) {
            if ($vm['show'] == '1') {
                $rolesMenu = [
                    "role_id" => $this->set_id,
                    "menu_id" => $vm['id'],
                    "is_show" => '1',
                    "is_create" => isset($vm['create']) ? '1' : '0',
                    "is_update" => isset($vm['update']) ? '1' : '0',
                    "is_delete" => isset($vm['delete']) ? '1' : '0',
                ];

                PrmRoleMenus::create($rolesMenu);

                foreach ($vm['children'] as $ksm => $vsm) {
                    if ($vsm['show'] == '1') {
                        $rolesMenu = [
                            "role_id" => $this->set_id,
                            "menu_id" => $vsm['id'],
                            "is_show" => '1',
                            "is_create" => isset($vsm['create']) ? '1' : '0',
                            "is_update" => isset($vsm['update']) ? '1' : '0',
                            "is_delete" => isset($vsm['delete']) ? '1' : '0',
                        ];

                        PrmRoleMenus::create($rolesMenu);
                    }
                }
            }
        }

        session()->flash('success', 'Saved');
        return redirect()->to('/masters/roles');
    }

    public function backRedirect()
    {
        return redirect()->to('/masters/roles');
    }
}
