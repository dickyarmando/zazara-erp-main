<?php

namespace App\Http\Livewire\Role;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Role;

class RoleTable extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    protected $listeners = [
        'roleStored' => 'render',
        'roleUpdated' => 'render',
        'roleDeleted' => 'render',
    ];
    public $orderColumn = "created_at";
    public $sortOrder = "desc";
    public $sortLink = '<i class="sorticon fa-solid fa-caret-up"></i>';
    public $name ;
    public $set_id;

    public function render()
    {
        $roles = Role::orderby($this->orderColumn,$this->sortOrder)->select('*');
        $roles = $roles->paginate(5);

        return view('livewire.role.role-table', [ 'roles' => $roles ]);
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
        $this->orderColumn = $columnName;

    }

    public function store()
    {
        $this->validate([
            'name'  => 'required|max:100',
        ]);

        $role = Role::create([
            'name' => $this->name,
        ]);

        $this->name = null;

        session()->flash('success','Role saved.');
        $this->dispatchBrowserEvent('close-modal');
    }

    public function edit($id)
    {
        $role = Role::find($id);
        if( $role ) {
            $this->set_id = $id;
            $this->name = $role->name;
        }else{
            return redirect()->to('/');
        }
    }

    public function update()
    {
        $this->validate([
            'name'  => 'required|max:100',
        ]);

        Role::find($this->set_id)->update([
            'name' => $this->name,
        ]);

        $this->name = null;

        session()->flash('success','Role updated.');
        $this->dispatchBrowserEvent('close-modal');
    }

    public function delete($id)
    {
        $this->set_id = $id;
    }

    public function destroy()
    {

        Role::destroy($this->set_id);
        session()->flash('success','Role deleted.');
        $this->dispatchBrowserEvent('close-modal');
    }
}
