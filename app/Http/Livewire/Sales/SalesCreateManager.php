<?php

namespace App\Http\Livewire\Sales;

use App\Models\MsCustomers;
use App\Models\PrmConfig;
use App\Models\TrSales;
use App\Models\TrSalesDetails;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class SalesCreateManager extends Component
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
    public $customer_id;
    public $customer_name;
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
        $countSales = TrSales::where(DB::raw('MONTH(date)'), $this->month)
            ->where(DB::raw('YEAR(date)'), $this->year)
            ->count();
        $this->sequence = $countSales + 1;
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
        $customers = MsCustomers::orderby($this->sortColumn, $this->sortOrder)
            ->select('id', 'code', 'name', 'company_name', 'phone');
        if (!empty($this->searchKeyword)) {
            $customers->orWhere('code', 'like', "%" . $this->searchKeyword . "%")->where('is_status', '1');
            $customers->orWhere('name', 'like', "%" . $this->searchKeyword . "%")->where('is_status', '1');
            $customers->orWhere('company_name', 'like', "%" . $this->searchKeyword . "%")->where('is_status', '1');
            $customers->orWhere('phone', 'like', "%" . $this->searchKeyword . "%")->where('is_status', '1');
        }
        $customers = $customers->where('is_status', '1')->paginate(10);

        return view('livewire.sales.sales-create-manager', ['customers' => $customers]);
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

    public function chooseCustomer($id)
    {
        $customer = MsCustomers::find($id);
        $this->customer_id = $customer->id;
        $this->customer_name = $customer->company_name;

        $this->dispatchBrowserEvent('close-modal');
    }

    public function backRedirect()
    {
        return redirect()->to('/sales');
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
            'customer_id' => 'required',
            'reference' => '',
            'notes' => '',
            'subtotal' => '',
            'discount' => '',
            'ppn' => '',
            'ppn_amount' => '',
            'total' => '',
        ];

        $numberOrder = 'SO/ESB/' . $this->month . $this->year . '/' . $this->number;

        if (empty($this->set_id)) {
            $countNumber = TrSales::where('number', $numberOrder)->count();

            if ($countNumber > 0) {
                $countSales = TrSales::where(DB::raw('MONTH(date)'), $this->month)
                    ->where(DB::raw('YEAR(date)'), $this->year)
                    ->count();
                $this->sequence = $countSales + 1;
                $this->number = str_pad($this->sequence, 4, "0", STR_PAD_LEFT);
                $numberOrder = 'SO/ESB/' . $this->month . $this->year . '/' . $this->number;
            }

            $valid = $this->validate($rules);
            $valid['number'] = $numberOrder;
            $valid['created_by'] = Auth::user()->id;
            $valid['updated_by'] = Auth::user()->id;
            $sales = TrSales::create($valid);

            foreach ($this->items as $key => $item) {
                if ($item['name'] != "") {
                    $dataDetail = [
                        'sales_id' => $sales->id,
                        'product_name' => $item['name'],
                        'unit_name' => $item['unit'],
                        'qty' => $item['qty'],
                        'rate' => $item['price'],
                        'amount' => $item['total'],
                    ];

                    TrSalesDetails::create($dataDetail);
                }
            }
        } else {
        }

        session()->flash('success', 'Saved ' . $numberOrder);
        return redirect()->to('/sales');
    }
}
