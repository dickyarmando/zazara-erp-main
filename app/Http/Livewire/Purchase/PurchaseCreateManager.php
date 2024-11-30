<?php

namespace App\Http\Livewire\Purchase;

use App\Models\MsSuppliers;
use App\Models\PrmConfig;
use App\Models\TrPurchase;
use App\Models\TrPurchaseDetails;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class PurchaseCreateManager extends Component
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
    public $supplier_id;
    public $supplier_name;
    public $notes;
    public $subtotal;
    public $ppn;
    public $ppn_amount;
    public $discount;
    public $total;
    public $items = [];

    public function mount()
    {
        $now = Carbon::now();
        $this->month = $now->month;
        $this->year = $now->year;
        $countPurchase = TrPurchase::where(DB::raw('MONTH(date)'), $this->month)
            ->where(DB::raw('YEAR(date)'), $this->year)
            ->count();
        $this->sequence = $countPurchase + 1;
        $this->number = str_pad($this->sequence, 4, "0", STR_PAD_LEFT);
        $this->date = $now->format('Y-m-d');

        $configPPN = PrmConfig::where('code', 'ppn')->first();
        $this->subtotal = 0;
        $this->ppn = $configPPN->value;
        $this->ppn_amount = 0;
        $this->discount = 0;
        $this->total = 0;

        for ($a = 0; $a < 4; $a++) {
            $this->add();
        }
    }

    public function render()
    {
        $suppliers = MsSuppliers::orderby($this->sortColumn, $this->sortOrder)
            ->select('id', 'code', 'name', 'company_name', 'phone');
        if (!empty($this->searchKeyword)) {
            $suppliers->orWhere('code', 'like', "%" . $this->searchKeyword . "%")->where('is_status', '1');
            $suppliers->orWhere('name', 'like', "%" . $this->searchKeyword . "%")->where('is_status', '1');
            $suppliers->orWhere('company_name', 'like', "%" . $this->searchKeyword . "%")->where('is_status', '1');
            $suppliers->orWhere('phone', 'like', "%" . $this->searchKeyword . "%")->where('is_status', '1');
        }
        $suppliers = $suppliers->where('is_status', '1')->paginate(10);

        return view('livewire.purchase.purchase-create-manager', ['suppliers' => $suppliers]);
    }

    public function add()
    {
        $this->items[] = [
            'name' => '',
            'unit' => '',
            'qty' => 1,
            'price' => 0,
            'total' => 0,
        ];
    }

    public function closeModal()
    {
        $this->resetErrorBag();
        $this->resetValidation();
        $this->dispatchBrowserEvent('close-modal');
    }

    public function chooseSupplier($id)
    {
        $supplier = MsSuppliers::find($id);
        $this->supplier_id = $supplier->id;
        $this->supplier_name = $supplier->company_name;

        $this->dispatchBrowserEvent('close-modal');
    }

    public function backRedirect()
    {
        return redirect()->to('/purchase');
    }

    public function calculate($id)
    {
        $item = $this->items[$id];
        $item['total'] = $item['qty'] * $item['price'];
        $this->items[$id] = $item;

        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        $this->subtotal = array_sum(array_column($this->items, 'total'));
        $this->ppn_amount = $this->subtotal * $this->ppn / 100;
        $this->total = ($this->subtotal - $this->discount) + $this->ppn_amount;
    }

    public function remove($index)
    {
        unset($this->items[$index]);

        $this->calculateTotal();
    }

    public function store()
    {
        $rules = [
            'number' => 'required',
            'date' => 'required',
            'supplier_id' => 'required',
            'reference' => '',
            'notes' => '',
            'subtotal' => '',
            'discount' => '',
            'ppn' => '',
            'ppn_amount' => '',
            'total' => '',
        ];

        $numberOrder = 'PO/ESB/' . $this->month . $this->year . '/' . $this->number;

        if (empty($this->set_id)) {
            $countNumber = TrPurchase::where('number', $numberOrder)->count();

            if ($countNumber > 0) {
                $countPurchase = TrPurchase::where(DB::raw('MONTH(date)'), $this->month)
                    ->where(DB::raw('YEAR(date)'), $this->year)
                    ->count();
                $this->sequence = $countPurchase + 1;
                $this->number = str_pad($this->sequence, 4, "0", STR_PAD_LEFT);
                $numberOrder = 'PO/ESB/' . $this->month . $this->year . '/' . $this->number;
            }

            $valid = $this->validate($rules);
            $valid['number'] = $numberOrder;
            $valid['created_by'] = Auth::user()->id;
            $valid['updated_by'] = Auth::user()->id;
            $purchase = TrPurchase::create($valid);

            foreach ($this->items as $key => $item) {
                if ($item['name'] != "") {
                    $dataDetail = [
                        'purchase_id' => $purchase->id,
                        'product_name' => $item['name'],
                        'unit_name' => $item['unit'],
                        'qty' => $item['qty'],
                        'rate' => $item['price'],
                        'amount' => $item['total'],
                    ];

                    TrPurchaseDetails::create($dataDetail);
                }
            }
        } else {
        }

        session()->flash('success', 'Saved ' . $numberOrder);
        return redirect()->to('/purchase');
    }
}
