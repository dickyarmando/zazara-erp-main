<?php

namespace App\Http\Livewire\Sales;

use App\Models\MsCustomers;
use App\Models\PrmRoleMenus;
use App\Models\TrSalesNon;
use App\Models\TrSalesNonDetails;
use App\Models\TrSalesNonFiles;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class SalesNonCreateManager extends Component
{
    use WithPagination, WithFileUploads;

    protected $paginationTheme = 'bootstrap';
    public $sortColumn = "company_name";
    public $sortOrder = "asc";
    public $sortLink = '<i class="sorticon fa-solid fa-caret-up"></i>';
    public $searchKeyword = '';
    public $set_id;
    public $set_id_file;

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
        $this->userRoles = PrmRoleMenus::where('menu_id', '31')->where('role_id', Auth::user()->role_id)->first();

        if (isset($_REQUEST['id'])) {
            $sales = TrSalesNon::find($_REQUEST['id']);
            $customer = MsCustomers::find($sales->customer_id);
            $salesDetails = TrSalesNonDetails::where('sales_non_id', $sales->id)
                ->select('id', 'product_name as name', 'unit_name as unit', 'qty', 'rate as price', 'amount as total')
                ->get()->toArray();
            $this->salesFiles = TrSalesNonFiles::where('sales_non_id', $sales->id)->get();
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
            $this->subtotal = $sales->subtotal;
            $this->delivery_fee = $sales->delivery_fee;
            $this->discount = $sales->discount;
            $this->total = $sales->total;
            $this->items = $salesDetails;
            if (isset($sales->approved_at)) {
                $this->is_approved = '1';
            }
        } else {
            $this->month = $now->month;
            $this->year = $now->year;
            $countSales = TrSalesNon::where(DB::raw('MONTH(created_at)'), $this->month)
                ->where(DB::raw('YEAR(created_at)'), $this->year)
                ->count();
            $this->sequence = $countSales + 1;
            $this->number = str_pad($this->sequence, 4, "0", STR_PAD_LEFT);
            $this->date = $now->format('Y-m-d');
            $this->sales_id = Auth::user()->id;
            $this->sales_name = Auth::user()->name;

            $this->subtotal = 0;
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

        return view('livewire.sales.sales-non-create-manager', ['customers' => $customers]);
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
        return redirect()->to('/sales/non-tax');
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
        $this->total = ($this->subtotal - $this->discount) + $this->delivery_fee;
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
                'total' => '',
            ];

            $numberOrder = 'SO/ESB-N/' . $this->month . $this->year . '/' . $this->number;

            $countNumber = TrSalesNon::where('number', $numberOrder)->count();

            if ($countNumber > 0) {
                $countSales = TrSalesNon::where(DB::raw('MONTH(created_at)'), $this->month)
                    ->where(DB::raw('YEAR(created_at)'), $this->year)
                    ->count();
                $this->sequence = $countSales + 1;
                $this->number = str_pad($this->sequence, 4, "0", STR_PAD_LEFT);
                $numberOrder = 'SO/ESB-N/' . $this->month . $this->year . '/' . $this->number;
            }

            $valid = $this->validate($rules);
            $valid['rest'] = $this->total;
            $valid['number'] = $numberOrder;
            $valid['created_by'] = Auth::user()->id;
            $valid['updated_by'] = Auth::user()->id;
            $sales = TrSalesNon::create($valid);

            foreach ($this->items as $key => $item) {
                if ($item['name'] != "") {
                    $dataDetail = [
                        'sales_non_id' => $sales->id,
                        'product_name' => $item['name'],
                        'unit_name' => $item['unit'],
                        'qty' => $item['qty'],
                        'rate' => $item['price'],
                        'amount' => $item['total'],
                    ];

                    TrSalesNonDetails::create($dataDetail);
                }
            }

            foreach ($this->files as $file) {
                $filename = $file->store('/', 'sales_non_disk');

                if ($filename) {
                    $dataFiles = [
                        'sales_non_id' => $sales->id,
                        'file' => $filename,
                    ];

                    TrSalesNonFiles::create($dataFiles);
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
                'total' => '',
            ];

            $valid = $this->validate($rules);
            $valid['rest'] = $this->total;
            $valid['updated_by'] = Auth::user()->id;
            $sales = TrSalesNon::find($this->set_id);
            $sales->update($valid);

            TrSalesNonDetails::where('sales_non_id', $this->set_id)->delete();
            foreach ($this->items as $key => $item) {
                if ($item['name'] != "") {
                    $dataDetail = [
                        'sales_non_id' => $this->set_id,
                        'product_name' => $item['name'],
                        'unit_name' => $item['unit'],
                        'qty' => $item['qty'],
                        'rate' => $item['price'],
                        'amount' => $item['total'],
                    ];

                    TrSalesNonDetails::create($dataDetail);
                }
            }

            foreach ($this->files as $file) {
                $filename = $file->store('/', 'sales_non_disk');

                if ($filename) {
                    $dataFiles = [
                        'sales_non_id' => $sales->id,
                        'file' => $filename,
                    ];

                    TrSalesNonFiles::create($dataFiles);
                }
            }

            $numberOrder = $sales->number;
        }

        session()->flash('success', 'Saved ' . $numberOrder);
        return redirect()->to('/sales/non-tax');
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
        $file = TrSalesNonFiles::find($this->set_id_file);
        $filePath = public_path('sales_non_files/' . $file->file);

        if (file_exists($filePath)) {
            unset($filePath);
        }

        $file->delete();
        $this->dispatchBrowserEvent('close-modal');

        $this->salesFiles = TrSalesNonFiles::where('sales_non_id', $this->set_id)->get();
        $this->set_id_file = null;
    }
}
