<?php

namespace App\Http\Controllers;

use App\Models\TrGeneralLedger;
use Illuminate\Http\Request;

class ExpanseTableReportController extends Controller
{
    public $start_date;
    public $end_date;
    public $searchKeyword;

    public function index(Request $request)
    {
        $start_date = $this->start_date = $request->sd;
        $end_date = $this->end_date = $request->ed;
        $this->searchKeyword = $request->s;

        $queryGL = TrGeneralLedger::select('tr_general_ledgers.id', 'tr_general_ledgers.number', 'tr_general_ledgers.date', 'tr_general_ledgers.reference', 'tr_general_ledgers.total_debit', 'tr_general_ledgers.total_credit', 'tr_general_ledgers.notes', 'tr_general_ledgers.is_status');

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
        $generalLedgers = $queryGL->whereBetween('tr_general_ledgers.date', [$this->start_date, $this->end_date])->get();

        return view('livewire.exports.expanse-table-report', compact('generalLedgers', 'summaryGL', 'start_date', 'end_date'));
    }
}
