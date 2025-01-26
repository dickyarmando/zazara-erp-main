<?php

namespace App\Http\Livewire\Masters;

use App\Models\MsProducts;
use Livewire\Component;
use Livewire\WithPagination;

class ProductsManager extends Component
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
        $queryProducts = MsProducts::orderBy($this->sortColumn, $this->sortOrder)
            ->select('id', 'name', 'is_status');

        if (!empty($this->searchKeyword)) {
            $queryProducts->orWhere('name', 'like', "%" . $this->searchKeyword . "%")->where('is_status', '1');
        }

        $products = $queryProducts->where('is_status', '1')->paginate($this->perPage);

        return view('livewire.masters.products-manager', ['products' => $products]);
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

        $countProduct = MsProducts::where('name', $this->name)
            ->where('is_status', '1');

        if (empty($this->set_id)) {
            if ($countProduct->count() > 0) {
                session()->flash('error', 'Failed, Product already exist..');
                return false;
            }

            MsProducts::create($valid);
        } else {
            $countProduct = $countProduct->where('id', '!=', $this->set_id)
                ->count();

            if ($countProduct > 0) {
                session()->flash('error', 'Failed, Product already exist..');
                return false;
            }

            $valid = $this->validate($rules);
            $product = MsProducts::find($this->set_id);
            $product->update($valid);
        }

        $this->formReset();
        session()->flash('success', 'Saved');
        $this->dispatchBrowserEvent('close-modal');
    }

    public function edit($id)
    {
        $product = MsProducts::find($id);
        $this->set_id = $id;
        $this->name = $product->name;
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

        $tp = MsProducts::find($this->set_id);
        $tp->update($valid);

        $this->formReset();
        session()->flash('success', 'Deleted');
        $this->dispatchBrowserEvent('close-modal');
    }
}
