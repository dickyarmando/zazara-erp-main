<?php

namespace App\Http\Livewire\Masters;

use App\Models\PrmRoles;
use Livewire\Component;
use Livewire\WithPagination;

class RolesManager extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $perPage = 10;
    public $sortColumn = "name";
    public $sortOrder = "asc";
    public $sortLink = '<i class="sorticon fa-solid fa-caret-up"></i>';
    public $searchKeyword = '';
    public $roleFilter = '';
    public $set_id;

    public function render()
    {
        $queryRoles = PrmRoles::orderBy($this->sortColumn, $this->sortOrder)
            ->select('id', 'name', 'is_status');

        if (!empty($this->searchKeyword)) {
            $queryRoles->orWhere('name', 'like', "%" . $this->searchKeyword . "%")->where('is_status', '1');
        }

        $roles = $queryRoles->where('is_status', '1')->paginate($this->perPage);

        return view('livewire.masters.roles-manager', ['roles' => $roles]);
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
        return redirect()->to('/masters/roles/create?id=' . $id);
    }
}
