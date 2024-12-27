<?php

namespace App\Http\Livewire\Invoice;

use App\Models\MsCustomers;
use App\Models\TrInvoice;
use App\Models\TrSales;
use App\Models\TrSalesDetails;
use App\Models\TrSalesFiles;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class InvoiceCreateManager extends Component
{
    public $set_id;
    public $month;
    public $year;
    public $sequence;

    public $number;
    public $date;
    public $sales_id;
    public $reference;
    public $customer_id;
    public $customer_name;
    public $due_termin;
    public $notes;
    public $subtotal;
    public $ppn;
    public $ppn_amount;
    public $discount;
    public $delivery_fee;
    public $total;

    public $items = [];
    public $salesFiles = [];

    public function mount($so)
    {
        $now = Carbon::now();

        $sales = TrSales::find($so);
        $customer = MsCustomers::find($sales->customer_id);
        $salesDetails = TrSalesDetails::where('sales_id', $sales->id)
            ->select('id', 'product_name as name', 'unit_name as unit', 'qty', 'rate as price', 'amount as total')
            ->get()->toArray();
        $this->salesFiles = TrSalesFiles::where('sales_id', $sales->id)->get();

        $this->month = $now->month;
        $this->year = $now->year;
        $countInv = TrInvoice::where(DB::raw('MONTH(created_at)'), $this->month)
            ->where(DB::raw('YEAR(created_at)'), $this->year)
            ->count();
        $this->sequence = $countInv + 1;
        $this->number = str_pad($this->sequence, 4, "0", STR_PAD_LEFT);
        $this->sales_id = $sales->id;
        $this->date = $sales->date;
        $this->reference = $sales->number;
        $this->customer_id = $sales->customer_id;
        $this->customer_name = $customer->company_name;
        $this->due_termin = 0;
        $this->subtotal = $sales->subtotal;
        $this->ppn = $sales->ppn;
        $this->ppn_amount = $sales->ppn_amount;
        $this->delivery_fee = $sales->delivery_fee;
        $this->discount = $sales->discount;
        $this->total = $sales->total;
        $this->items = $salesDetails;
    }

    public function render()
    {
        return view('livewire.invoice.invoice-create-manager');
    }

    public function backRedirect()
    {
        return redirect()->to('/sales');
    }

    public function store()
    {
        if (empty($this->set_id)) {
            $rules = [
                'number' => 'required',
                'date' => 'required',
                'customer_id' => 'required',
                'sales_id' => 'required',
                'due_termin' => 'required',
                'notes' => '',
                'subtotal' => '',
                'delivery_fee' => '',
                'discount' => '',
                'ppn' => '',
                'ppn_amount' => '',
                'total' => '',
            ];

            $numberOrder = 'INV/ESB/' . $this->month . $this->year . '/' . $this->number;

            $countNumber = TrInvoice::where('number', $numberOrder)->count();

            if ($countNumber > 0) {
                $countInv = TrInvoice::where(DB::raw('MONTH(created_at)'), $this->month)
                    ->where(DB::raw('YEAR(created_at)'), $this->year)
                    ->count();
                $this->sequence = $countInv + 1;
                $this->number = str_pad($this->sequence, 4, "0", STR_PAD_LEFT);
                $numberOrder = 'INV/ESB/' . $this->month . $this->year . '/' . $this->number;
            }

            $date = Carbon::parse($this->date);
            $due_date = $date->addDays($this->due_termin);

            $valid = $this->validate($rules);
            $valid['rest'] = $this->total;
            $valid['number'] = $numberOrder;
            $valid['due_date'] = $due_date;
            $valid['created_by'] = Auth::user()->id;
            $valid['updated_by'] = Auth::user()->id;
            TrInvoice::create($valid);

            $salesData = [
                'is_invoice' => '1',
                'updated_at' => Carbon::now()->toDateTimeString(),
                'updated_by' => Auth::user()->id,
            ];
            $sales = TrSales::find($this->sales_id);
            $sales->update($salesData);
        }
        // else {
        //     $rules = [
        //         'number' => 'required',
        //         'date' => 'required',
        //         'customer_id' => 'required',
        //         'sales_id' => 'required',
        //         'due_termin' => 'required',
        //         'notes' => '',
        //         'subtotal' => '',
        //         'delivery_fee' => '',
        //         'discount' => '',
        //         'ppn' => '',
        //         'ppn_amount' => '',
        //         'total' => '',
        //     ];

        //     $valid = $this->validate($rules);
        //     $valid['rest'] = $this->total;
        //     $valid['updated_by'] = Auth::user()->id;
        //     $sales = TrSales::find($this->set_id);
        //     $sales->update($valid);

        //     TrSalesDetails::where('sales_id', $this->set_id)->delete();
        //     foreach ($this->items as $key => $item) {
        //         if ($item['name'] != "") {
        //             $dataDetail = [
        //                 'sales_id' => $this->set_id,
        //                 'product_name' => $item['name'],
        //                 'unit_name' => $item['unit'],
        //                 'qty' => $item['qty'],
        //                 'rate' => $item['price'],
        //                 'amount' => $item['total'],
        //             ];

        //             TrSalesDetails::create($dataDetail);
        //         }
        //     }

        //     foreach ($this->files as $file) {
        //         $filename = $file->store('/', 'sales_disk');

        //         if ($filename) {
        //             $dataFiles = [
        //                 'sales_id' => $sales->id,
        //                 'file' => $filename,
        //             ];

        //             TrSalesFiles::create($dataFiles);
        //         }
        //     }

        //     $numberOrder = $sales->number;
        // }

        session()->flash('success', 'Saved ' . $numberOrder);
        return redirect()->to('/sales');
    }
}
