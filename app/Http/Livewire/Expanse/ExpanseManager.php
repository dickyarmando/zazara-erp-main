<?php

namespace App\Http\Livewire\Expanse;

use App\Models\TrGeneralLedger;
use Livewire\Component;
use Livewire\WithPagination;

class ExpanseManager extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $perPage = 10;
    public $sortColumn = "tr_general_ledgers.number";
    public $sortOrder = "desc";
    public $sortLink = '<i class="sorticon fa-solid fa-caret-up"></i>';
    public $searchKeyword = '';
    public $roleFilter = '';
    public $set_id;

    public function render()
    {
        $queryGL = TrGeneralLedger::orderBy($this->sortColumn, $this->sortOrder)
            ->select('tr_general_ledgers.id', 'tr_general_ledgers.number', 'tr_general_ledgers.date', 'tr_general_ledgers.reference', 'tr_general_ledgers.total_debit', 'tr_general_ledgers.total_credit', 'tr_general_ledgers.notes', 'tr_general_ledgers.is_status');

        if (!empty($this->searchKeyword)) {
            $queryGL->orWhere('tr_general_ledgers.number', 'like', "%" . $this->searchKeyword . "%");
            $queryGL->orWhere('tr_general_ledgers.notes', 'like', "%" . $this->searchKeyword . "%");
            $queryGL->orWhere('tr_general_ledgers.total_debit', 'like', "%" . $this->searchKeyword . "%");
        }

        $generalLedgers = $queryGL->paginate($this->perPage);

        return view('livewire.expanse.expanse-manager', compact('generalLedgers'));
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

    public function view($id)
    {
        return redirect()->to('/expanse/view/' . $id);
    }

    public function edit($id)
    {
        return redirect()->to('/expanse/create?id=' . $id);
    }
}
