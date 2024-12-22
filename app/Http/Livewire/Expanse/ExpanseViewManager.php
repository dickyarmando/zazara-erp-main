<?php

namespace App\Http\Livewire\Expanse;

use App\Models\PrmCompanies;
use App\Models\TrGeneralLedger;
use App\Models\TrGeneralLedgerDetails;
use Livewire\Component;

class ExpanseViewManager extends Component
{
    public $set_id;

    public function mount()
    {
        $this->set_id = request()->id;
    }

    public function render()
    {
        $companies = PrmCompanies::find(1);
        $trGl = TrGeneralLedger::find($this->set_id);
        $trGlDetails = TrGeneralLedgerDetails::leftJoin('ms_accounts', 'ms_accounts.id', '=', 'tr_general_ledger_details.account_id')
            ->select('tr_general_ledger_details.*', 'ms_accounts.code as account_code', 'ms_accounts.name as account_name')
            ->where('general_ledger_id', $trGl->id)
            ->get();

        return view('livewire.expanse.expanse-view-manager', compact('companies', 'trGl', 'trGlDetails'));
    }

    public function backRedirect()
    {
        return redirect()->to('/expanse');
    }

    public function printDocument()
    {
        $this->dispatchBrowserEvent('print');
    }
}
