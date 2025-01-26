<?php

namespace App\Http\Livewire\Expanse;

use App\Http\Controllers\Controller;
use App\Models\PrmCompanies;
use App\Models\TrGeneralLedger;
use App\Models\TrGeneralLedgerDetails;

class ExpanseViewPrintManager extends Controller
{
    public function index($id)
    {
        $companies = PrmCompanies::find(1);
        $trGl = TrGeneralLedger::find($id);
        $trGlDetails = TrGeneralLedgerDetails::leftJoin('ms_accounts', 'ms_accounts.id', '=', 'tr_general_ledger_details.account_id')
            ->select('tr_general_ledger_details.*', 'ms_accounts.code as account_code', 'ms_accounts.name as account_name')
            ->where('general_ledger_id', $trGl->id)
            ->get();

        return view('livewire.expanse.expanse-view-print-manager', compact('companies', 'trGl', 'trGlDetails'));
    }
}
