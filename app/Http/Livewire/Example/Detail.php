<?php

namespace App\Http\Livewire\Example;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;

class Detail extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $sortColumn = "created_at";
    public $sortOrder = "desc";
    public $sortLink = '<i class="sorticon fa-solid fa-caret-up"></i>';
    public $searchKeyword = '';

    public $index = '';
    public $items = [];

    public function mount()
    {
        $this->add();
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

    public function render()
    {
        $users = User::orderby($this->sortColumn,$this->sortOrder)->select('*');
        if(!empty($this->searchKeyword)){
            $users->orWhere('name','like',"%".$this->searchKeyword."%");
            $users->orWhere('email','like',"%".$this->searchKeyword."%");
        }
        $users = $users->paginate(10);

        return view('livewire.example.detail', ['users' => $users]);
    }

    public function setIndex($id)
    {
        $this->index = $id;
    }
    public function choose($id)
    {
        $user = User::find($id);
        $this->items[$this->index]['name'] = $user->name;

        $this->index = '';
        $this->dispatchBrowserEvent('close-modal');
    }

    public function add()
    {
        $this->items[] = [
            'name' => '',
            'qty' => '0',
        ];
    }

    public function remove($index)
    {
        unset($this->items[$index]);
    }

    public function closeModal()
    {
        $this->resetErrorBag();
        $this->resetValidation();
        $this->dispatchBrowserEvent('close-modal');
    }
}
