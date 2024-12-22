<?php

namespace App\Http\Livewire\Reports;

use App\Models\TrGeneralLedger;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class ExpanseReportManager extends Component
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

    public $start_date;
    public $end_date;

    public function mount()
    {
        $now = Carbon::now();

        $this->start_date = $now->startOfMonth()->format('Y-m-d');
        $this->end_date = $now->endOfMonth()->format('Y-m-d');
    }

    public function render()
    {
        $queryGL = TrGeneralLedger::orderBy($this->sortColumn, $this->sortOrder)
            ->select('tr_general_ledgers.id', 'tr_general_ledgers.number', 'tr_general_ledgers.date', 'tr_general_ledgers.reference', 'tr_general_ledgers.total_debit', 'tr_general_ledgers.total_credit', 'tr_general_ledgers.notes', 'tr_general_ledgers.is_status');

        $querySummaryGL = TrGeneralLedger::selectRaw('SUM(tr_general_ledgers.total_debit) as total_debit, SUM(tr_general_ledgers.total_credit) as total_credit');

        if (!empty($this->searchKeyword)) {
            $queryGL->orWhere('tr_general_ledgers.number', 'like', "%" . $this->searchKeyword . "%")->whereBetween('tr_general_ledgers.date', [$this->start_date, $this->end_date]);
            $queryGL->orWhere('tr_general_ledgers.notes', 'like', "%" . $this->searchKeyword . "%")->whereBetween('tr_general_ledgers.date', [$this->start_date, $this->end_date]);
            $queryGL->orWhere('tr_general_ledgers.total_debit', 'like', "%" . $this->searchKeyword . "%")->whereBetween('tr_general_ledgers.date', [$this->start_date, $this->end_date]);

            $querySummaryGL->orWhere('tr_general_ledgers.number', 'like', "%" . $this->searchKeyword . "%")->whereBetween('tr_general_ledgers.date', [$this->start_date, $this->end_date]);
            $querySummaryGL->orWhere('tr_general_ledgers.notes', 'like', "%" . $this->searchKeyword . "%")->whereBetween('tr_general_ledgers.date', [$this->start_date, $this->end_date]);
            $querySummaryGL->orWhere('tr_general_ledgers.total_debit', 'like', "%" . $this->searchKeyword . "%")->whereBetween('tr_general_ledgers.date', [$this->start_date, $this->end_date]);
        }

        $summaryGL = $querySummaryGL->whereBetween('tr_general_ledgers.date', [$this->start_date, $this->end_date])->first();
        $generalLedgers = $queryGL->whereBetween('tr_general_ledgers.date', [$this->start_date, $this->end_date])->paginate($this->perPage);

        return view('livewire.reports.expanse-report-manager', compact('generalLedgers', 'summaryGL'));
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
}
