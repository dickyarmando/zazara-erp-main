<?php

namespace App\Http\Livewire\Sales;

use App\Models\MsCustomers;
use App\Models\MsProducts;
use App\Models\PrmConfig;
use App\Models\PrmRoleMenus;
use App\Models\TrSales;
use App\Models\TrSalesDetails;
use App\Models\TrSalesFiles;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class SalesCreateManager extends Component
{
    use WithPagination, WithFileUploads;

    protected $paginationTheme = 'bootstrap';
    public $sortColumn = "company_name";
    public $sortOrder = "asc";
    public $sortLink = '<i class="sorticon fa-solid fa-caret-up"></i>';
    public $searchKeyword = '';
    public $set_id;
    public $set_id_file;
    public $set_index;

    public $sortColumnItem = "name";
    public $sortOrderItem = "asc";
    public $sortLinkItem = '<i class="sorticon fa-solid fa-caret-up"></i>';
    public $searchKeywordItem = '';

    public $month;
    public $year;
    public $sequence;

    public $number;
    public $date;
    public $reference;
    public $customer_id;
    public $customer_name;
    public $sales_id;
    public $sales_name;
    public $notes;
    public $subtotal;
    public $ppn;
    public $ppn_amount;
    public $discount;
    public $delivery_fee;
    public $total;
    public $is_approved;

    public $items = [];
    public $files = [];
    public $salesFiles = [];
    public $salesList = [];
    public $userRoles = [];

    public function mount()
    {
        $now = Carbon::now();
        $this->salesList = User::where('is_status', '1')->get();
        $this->userRoles = PrmRoleMenus::where('menu_id', '30')->where('role_id', Auth::user()->role_id)->first();

        if (isset($_REQUEST['id'])) {
            $sales = TrSales::find($_REQUEST['id']);
            $customer = MsCustomers::find($sales->customer_id);
            $salesDetails = TrSalesDetails::where('sales_id', $sales->id)
                ->select('id', 'product_code as code', 'product_name as name', 'unit_name as unit', DB::raw('CEIL(qty) as qty'), DB::raw('CEIL(rate) as price'), DB::raw('CEIL(amount) as total'))
                ->get()->toArray();
            $this->salesFiles = TrSalesFiles::where('sales_id', $sales->id)->get();
            $sequence = explode("/", $sales->number);
            $userSales = User::find($sales->sales_id);

            $this->set_id = $sales->id;
            $this->month = $sales->created_at->format('m');
            $this->year = $sales->created_at->format('Y');
            $this->number = $sequence[3];
            $this->date = $sales->date;
            $this->reference = $sales->reference;
            $this->customer_id = $sales->customer_id;
            $this->customer_name = $customer->name;
            $this->sales_id = $sales->sales_id;
            $this->sales_name = isset($userSales->name) ? $userSales->name : "";
            $this->notes = $sales->notes;
            $this->subtotal = number_format($sales->subtotal, 0, '.', '');
            $this->ppn = number_format($sales->ppn, 0, '.', '');
            $this->ppn_amount = number_format($sales->ppn_amount, 0, '.', '');
            $this->delivery_fee = number_format($sales->delivery_fee, 0, '.', '');
            $this->discount = number_format($sales->discount, 0, '.', '');
            $this->total = number_format($sales->total, 0, '.', '');
            $this->items = $salesDetails;
            if (isset($sales->approved_at)) {
                $this->is_approved = '1';
            }
        } else {
            $this->month = $now->month;
            $this->year = $now->year;
            $countSales = TrSales::where(DB::raw('MONTH(created_at)'), $this->month)
                ->where(DB::raw('YEAR(created_at)'), $this->year)
                ->count();
            $this->sequence = $countSales + 1;
            $this->number = str_pad($this->sequence, 4, "0", STR_PAD_LEFT);
            $this->date = $now->format('Y-m-d');
            $this->sales_id = Auth::user()->id;
            $this->sales_name = Auth::user()->name;

            $configPPN = PrmConfig::where('code', 'ppn')->first();
            $this->subtotal = 0;
            $this->ppn = $configPPN->value;
            $this->ppn_amount = 0;
            $this->delivery_fee = 0;
            $this->discount = 0;
            $this->total = 0;

            for ($a = 0; $a < 4; $a++) {
                $this->add();
            }
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

        $products = MsProducts::orderby($this->sortColumnItem, $this->sortOrderItem)
            ->select('id', 'name', 'is_status');

        if (!empty($this->searchKeywordItem)) {
            $products->orWhere('name', 'like', "%" . $this->searchKeywordItem . "%")->where('is_status', '1');
        }

        $products = $products->where('is_status', '1')->paginate(10);

        return view('livewire.sales.sales-create-manager', compact('customers', 'products'));
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

    public function sortOrderItem($columnName = "")
    {
        $caretOrder = "up";
        if ($this->sortOrderItem == 'asc') {
            $this->sortOrderItem = 'desc';
            $caretOrder = "down";
        } else {
            $this->sortOrderItem = 'asc';
            $caretOrder = "up";
        }
        $this->sortLinkItem = '<i class="sorticon fa-solid fa-caret-' . $caretOrder . '"></i>';
        $this->sortColumnItem = $columnName;
    }

    public function add()
    {
        $this->items[] = [
            'code' => '',
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
        $item['total'] = number_format($item['qty'] * $item['price'], 2, '.', '');
        $this->items[$id] = $item;

        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        $this->subtotal = number_format(array_sum(array_column($this->items, 'total')), 0, '.', '');
        $this->ppn_amount = number_format($this->subtotal * $this->ppn / 100, 0, '.', '');
        $this->total = number_format(($this->subtotal - $this->discount) + $this->ppn_amount + $this->delivery_fee, 0, '.', '');
    }

    public function remove($index)
    {
        unset($this->items[$index]);

        $this->calculateTotal();
    }

    public function store()
    {
        if ($this->files) {
            $this->validate([
                'files.*' => 'required|mimes:pdf,png,jpg|max:2048',
            ]);
        }

        if (empty($this->set_id)) {
            $rules = [
                'number' => 'required',
                'date' => 'required',
                'customer_id' => 'required',
                'sales_id' => 'required',
                'reference' => '',
                'notes' => '',
                'subtotal' => '',
                'delivery_fee' => '',
                'discount' => '',
                'ppn' => '',
                'ppn_amount' => '',
                'total' => '',
            ];

            $numberOrder = 'SO/ESB/' . $this->month . $this->year . '/' . $this->number;

            $countNumber = TrSales::where('number', $numberOrder)->count();

            if ($countNumber > 0) {
                $countSales = TrSales::where(DB::raw('MONTH(created_at)'), $this->month)
                    ->where(DB::raw('YEAR(created_at)'), $this->year)
                    ->count();
                $this->sequence = $countSales + 1;
                $this->number = str_pad($this->sequence, 4, "0", STR_PAD_LEFT);
                $numberOrder = 'SO/ESB/' . $this->month . $this->year . '/' . $this->number;
            }

            $valid = $this->validate($rules);
            $valid['rest'] = $this->total;
            $valid['number'] = $numberOrder;
            $valid['created_by'] = Auth::user()->id;
            $valid['updated_by'] = Auth::user()->id;
            $sales = TrSales::create($valid);

            foreach ($this->items as $key => $item) {
                if ($item['name'] != "") {
                    $dataDetail = [
                        'sales_id' => $sales->id,
                        'product_code' => $item['code'],
                        'product_name' => $item['name'],
                        'unit_name' => $item['unit'],
                        'qty' => $item['qty'],
                        'rate' => $item['price'],
                        'amount' => $item['total'],
                    ];

                    TrSalesDetails::create($dataDetail);

                    $products = MsProducts::where('name', $item['name'])->where('is_status', '1')->first();
                    if (!isset($products->id)) {
                        $products = MsProducts::create([
                            'name' => $item['name'],
                        ]);
                    }
                }
            }

            foreach ($this->files as $file) {
                $filename = $file->store('/', 'sales_disk');

                if ($filename) {
                    $dataFiles = [
                        'sales_id' => $sales->id,
                        'file' => $filename,
                    ];

                    TrSalesFiles::create($dataFiles);
                }
            }
        } else {
            $rules = [
                'customer_id' => 'required',
                'sales_id' => 'required',
                'reference' => '',
                'notes' => '',
                'subtotal' => '',
                'delivery_fee' => '',
                'discount' => '',
                'ppn' => '',
                'ppn_amount' => '',
                'total' => '',
            ];

            $valid = $this->validate($rules);
            $valid['rest'] = $this->total;
            $valid['updated_by'] = Auth::user()->id;
            $sales = TrSales::find($this->set_id);
            $sales->update($valid);

            TrSalesDetails::where('sales_id', $this->set_id)->delete();
            foreach ($this->items as $key => $item) {
                if ($item['name'] != "") {
                    $dataDetail = [
                        'sales_id' => $this->set_id,
                        'product_code' => $item['code'],
                        'product_name' => $item['name'],
                        'unit_name' => $item['unit'],
                        'qty' => $item['qty'],
                        'rate' => $item['price'],
                        'amount' => $item['total'],
                    ];

                    TrSalesDetails::create($dataDetail);

                    $products = MsProducts::where('name', $item['name'])->where('is_status', '1')->first();
                    if (!isset($products->id)) {
                        $products = MsProducts::create([
                            'name' => $item['name'],
                        ]);
                    }
                }
            }

            foreach ($this->files as $file) {
                $filename = $file->store('/', 'sales_disk');

                if ($filename) {
                    $dataFiles = [
                        'sales_id' => $sales->id,
                        'file' => $filename,
                    ];

                    TrSalesFiles::create($dataFiles);
                }
            }

            $numberOrder = $sales->number;
        }

        session()->flash('success', 'Saved ' . $numberOrder);
        return redirect()->to('/sales');
    }

    public function updatedFiles()
    {
        $this->validate([
            'files.*' => 'required|mimes:pdf,png,jpg|max:2048',
        ]);
    }

    public function deleteFile($id)
    {
        $this->set_id_file = $id;
    }

    public function destroyFile()
    {
        $file = TrSalesFiles::find($this->set_id_file);
        $filePath = public_path('sales_files/' . $file->file);

        if (file_exists($filePath)) {
            unset($filePath);
        }

        $file->delete();
        $this->dispatchBrowserEvent('close-modal');

        $this->salesFiles = TrSalesFiles::where('sales_id', $this->set_id)->get();
        $this->set_id_file = null;
    }

    public function setIndex($index)
    {
        $this->set_index = $index;
    }

    public function chooseProducts($id)
    {
        $product = MsProducts::find($id);
        $this->items[$this->set_index]['name'] = $product->name;

        $this->dispatchBrowserEvent('close-modal');
    }
}
