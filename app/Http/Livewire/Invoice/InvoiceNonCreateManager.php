<?php

namespace App\Http\Livewire\Invoice;

use App\Models\MsCustomers;
use App\Models\TrInvoicesNon;
use App\Models\TrSalesNon;
use App\Models\TrSalesNonDetails;
use App\Models\TrSalesNonFiles;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class InvoiceNonCreateManager extends Component
{
    public $set_id;
    public $month;
    public $year;
    public $sequence;

    public $number;
    public $date;
    public $sales_non_id;
    public $reference;
    public $customer_id;
    public $customer_name;
    public $due_termin;
    public $notes;
    public $subtotal;
    public $discount;
    public $delivery_fee;
    public $total;

    public $items = [];
    public $salesFiles = [];

    public function mount($so)
    {
        $now = Carbon::now();

        $sales = TrSalesNon::find($so);
        $customer = MsCustomers::find($sales->customer_id);
        $salesDetails = TrSalesNonDetails::where('sales_non_id', $sales->id)
            ->select('id', 'product_name as name', 'unit_name as unit', DB::raw('CEIL(qty) as qty'), DB::raw('CEIL(rate) as price'), DB::raw('CEIL(amount) as total'))
            ->get()->toArray();
        $this->salesFiles = TrSalesNonFiles::where('sales_non_id', $sales->id)->get();

        $this->month = $now->month;
        $this->year = $now->year;
        $countInv = TrInvoicesNon::where(DB::raw('MONTH(created_at)'), $this->month)
            ->where(DB::raw('YEAR(created_at)'), $this->year)
            ->count();
        $this->sequence = $countInv + 1;
        $this->number = str_pad($this->sequence, 4, "0", STR_PAD_LEFT);
        $this->sales_non_id = $sales->id;
        $this->date = $sales->date;
        $this->reference = $sales->number;
        $this->customer_id = $sales->customer_id;
        $this->customer_name = $customer->company_name;
        $this->due_termin = 0;
        $this->subtotal = number_format($sales->subtotal, 0, '.', '');
        $this->delivery_fee = number_format($sales->delivery_fee, 0, '.', '');
        $this->discount = number_format($sales->discount, 0, '.', '');
        $this->total = number_format($sales->total, 0, '.', '');
        $this->items = $salesDetails;
    }

    public function render()
    {
        return view('livewire.invoice.invoice-non-create-manager');
    }

    public function backRedirect()
    {
        return redirect()->to('/sales/non-tax');
    }

    public function store()
    {
        if (empty($this->set_id)) {
            $rules = [
                'number' => 'required',
                'date' => 'required',
                'customer_id' => 'required',
                'sales_non_id' => 'required',
                'due_termin' => 'required',
                'notes' => '',
                'subtotal' => '',
                'delivery_fee' => '',
                'discount' => '',
                'total' => '',
            ];

            $numberOrder = 'INV/ESB-N/' . $this->month . $this->year . '/' . $this->number;

            $countNumber = TrInvoicesNon::where('number', $numberOrder)->count();

            if ($countNumber > 0) {
                $countInv = TrInvoicesNon::where(DB::raw('MONTH(created_at)'), $this->month)
                    ->where(DB::raw('YEAR(created_at)'), $this->year)
                    ->count();
                $this->sequence = $countInv + 1;
                $this->number = str_pad($this->sequence, 4, "0", STR_PAD_LEFT);
                $numberOrder = 'INV/ESB-N/' . $this->month . $this->year . '/' . $this->number;
            }

            $date = Carbon::parse($this->date);
            $due_date = $date->addDays($this->due_termin);

            $valid = $this->validate($rules);
            $valid['rest'] = $this->total;
            $valid['number'] = $numberOrder;
            $valid['due_date'] = $due_date;
            $valid['created_by'] = Auth::user()->id;
            $valid['updated_by'] = Auth::user()->id;
            TrInvoicesNon::create($valid);

            $salesData = [
                'is_invoice' => '1',
                'updated_at' => Carbon::now()->toDateTimeString(),
                'updated_by' => Auth::user()->id,
            ];
            $sales = TrSalesNon::find($this->sales_non_id);
            $sales->update($salesData);
        }

        session()->flash('success', 'Saved ' . $numberOrder);
        return redirect()->to('/sales/non-tax');
    }
}
