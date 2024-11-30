<?php

namespace App\Http\Livewire\Expanse;

use App\Models\TrGeneralLedger;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class ExpanseCreateManager extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $sortColumn = "company_name";
    public $sortOrder = "asc";
    public $sortLink = '<i class="sorticon fa-solid fa-caret-up"></i>';
    public $searchKeyword = '';
    public $set_id;

    public $month;
    public $year;
    public $sequence;

    public $number;
    public $date;
    public $reference;
    public $notes;
    public $total_credit;
    public $total_debit;

    public $items = [];

    public function mount()
    {
        $now = Carbon::now();
        $this->month = $now->month;
        $this->year = $now->year;

        $countGL = TrGeneralLedger::where(DB::raw('MONTH(date)'), $this->month)
            ->where(DB::raw('YEAR(date)'), $this->year)
            ->count();
        $this->sequence = $countGL + 1;
        $this->number = str_pad($this->sequence, 4, "0", STR_PAD_LEFT);
        $this->date = $now->format('Y-m-d');

        $this->total_debit = 0;
        $this->total_credit = 0;

        $this->add('cr');
        $this->add('db');
    }

    public function render()
    {
        return view('livewire.expanse.expanse-create-manager');
    }

    public function add($type)
    {
        $this->items[] = [
            'account_id' => '',
            'account_name' => '',
            'amount' => 0,
            'type' => $type,
        ];
    }
}
