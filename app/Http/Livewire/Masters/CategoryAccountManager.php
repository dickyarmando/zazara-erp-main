<?php

namespace App\Http\Livewire\Masters;

use App\Models\PrmCategoryAccount;
use Livewire\Component;
use Livewire\WithPagination;

class CategoryAccountManager extends Component
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

    public $name;

    public function render()
    {
        $queryCategory = PrmCategoryAccount::orderBy($this->sortColumn, $this->sortOrder)
            ->select('id', 'name', 'is_status');

        if (!empty($this->searchKeyword)) {
            $queryCategory->orWhere('name', 'like', "%" . $this->searchKeyword . "%")->where('is_status', '1');
        }

        $categories = $queryCategory->where('is_status', '1')->paginate($this->perPage);

        return view('livewire.masters.category-account-manager', ['categories' => $categories]);
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

        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function store()
    {
        $rules = [
            'name' => 'required|max:100',
        ];
        $valid = $this->validate($rules);

        $countCategory = PrmCategoryAccount::where('name', $this->name)
            ->where('is_status', '1');

        if (empty($this->set_id)) {
            if ($countCategory->count() > 0) {
                session()->flash('error', 'Failed, Category Account already exist..');
                return false;
            }

            PrmCategoryAccount::create($valid);
        } else {
            $countCategory = $countCategory->where('id', '!=', $this->set_id)
                ->count();

            if ($countCategory > 0) {
                session()->flash('error', 'Failed, Category Account already exist..');
                return false;
            }

            $valid = $this->validate($rules);
            $category = PrmCategoryAccount::find($this->set_id);
            $category->update($valid);
        }

        $this->formReset();
        session()->flash('success', 'Saved');
        $this->dispatchBrowserEvent('close-modal');
    }

    public function edit($id)
    {
        $category = PrmCategoryAccount::find($id);
        $this->set_id = $id;
        $this->name = $category->name;
    }

    public function delete($id)
    {
        $this->set_id = $id;
    }

    public function destroy()
    {
        $valid = [
            'is_status' => '0'
        ];

        $tp = PrmCategoryAccount::find($this->set_id);
        $tp->update($valid);

        $this->formReset();
        session()->flash('success', 'Deleted');
        $this->dispatchBrowserEvent('close-modal');
    }
}
