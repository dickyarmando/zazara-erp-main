<?php

namespace App\Http\Livewire\Purchase;

use App\Models\MsSuppliers;
use App\Models\TrPurchaseNon;
use App\Models\TrPurchaseNonDetails;
use App\Models\TrPurchaseNonFiles;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class PurchaseNonCreateManager extends Component
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
    public $supplier_id;
    public $supplier_name;
    public $notes;
    public $subtotal;
    public $discount;
    public $delivery_fee;
    public $total;

    public $items = [];
    public $files = [];
    public $purchaseFiles = [];

    public function mount()
    {
        $now = Carbon::now();

        if (isset($_REQUEST['id'])) {
            $purchase = TrPurchaseNon::find($_REQUEST['id']);
            $supplier = MsSuppliers::find($purchase->supplier_id);
            $purchaseDetails = TrPurchaseNonDetails::where('purchase_non_id', $purchase->id)
                ->select('id', 'product_name as name', 'unit_name as unit', 'qty', 'rate as price', 'amount as total')
                ->get()->toArray();
            $this->purchaseFiles = TrPurchaseNonFiles::where('purchase_non_id', $purchase->id)->get();
            $sequence = explode("/", $purchase->number);

            $this->set_id = $purchase->id;
            $this->month = $purchase->created_at->format('m');
            $this->year = $purchase->created_at->format('Y');
            $this->number = $sequence[3];
            $this->date = $purchase->date;
            $this->reference = $purchase->reference;
            $this->supplier_id = $purchase->supplier_id;
            $this->supplier_name = $supplier->name;
            $this->notes = $purchase->notes;
            $this->subtotal = $purchase->subtotal;
            $this->delivery_fee = $purchase->delivery_fee;
            $this->discount = $purchase->discount;
            $this->total = $purchase->total;
            $this->items = $purchaseDetails;
        } else {
            $this->month = $now->month;
            $this->year = $now->year;
            $countPurchase = TrPurchaseNon::where(DB::raw('MONTH(created_at)'), $this->month)
                ->where(DB::raw('YEAR(created_at)'), $this->year)
                ->count();
            $this->sequence = $countPurchase + 1;
            $this->number = str_pad($this->sequence, 4, "0", STR_PAD_LEFT);
            $this->date = $now->format('Y-m-d');

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
        $suppliers = MsSuppliers::orderby($this->sortColumn, $this->sortOrder)
            ->select('id', 'code', 'name', 'company_name', 'phone');
        if (!empty($this->searchKeyword)) {
            $suppliers->orWhere('code', 'like', "%" . $this->searchKeyword . "%")->where('is_status', '1');
            $suppliers->orWhere('name', 'like', "%" . $this->searchKeyword . "%")->where('is_status', '1');
            $suppliers->orWhere('company_name', 'like', "%" . $this->searchKeyword . "%")->where('is_status', '1');
            $suppliers->orWhere('phone', 'like', "%" . $this->searchKeyword . "%")->where('is_status', '1');
        }
        $suppliers = $suppliers->where('is_status', '1')->paginate(10);

        return view('livewire.purchase.purchase-non-create-manager', ['suppliers' => $suppliers]);
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
        return redirect()->to('/purchase/non-tax');
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
                'supplier_id' => 'required',
                'reference' => '',
                'notes' => '',
                'subtotal' => '',
                'delivery_fee' => '',
                'discount' => '',
                'total' => '',
            ];

            $numberOrder = 'PO/ESB-N/' . $this->month . $this->year . '/' . $this->number;

            $countNumber = TrPurchaseNon::where('number', $numberOrder)->count();

            if ($countNumber > 0) {
                $countPurchase = TrPurchaseNon::where(DB::raw('MONTH(created_at)'), $this->month)
                    ->where(DB::raw('YEAR(created_at)'), $this->year)
                    ->count();
                $this->sequence = $countPurchase + 1;
                $this->number = str_pad($this->sequence, 4, "0", STR_PAD_LEFT);
                $numberOrder = 'PO/ESB-N/' . $this->month . $this->year . '/' . $this->number;
            }

            $valid = $this->validate($rules);
            $valid['number'] = $numberOrder;
            $valid['created_by'] = Auth::user()->id;
            $valid['updated_by'] = Auth::user()->id;
            $purchase = TrPurchaseNon::create($valid);

            foreach ($this->items as $key => $item) {
                if ($item['name'] != "") {
                    $dataDetail = [
                        'purchase_non_id' => $purchase->id,
                        'product_name' => $item['name'],
                        'unit_name' => $item['unit'],
                        'qty' => $item['qty'],
                        'rate' => $item['price'],
                        'amount' => $item['total'],
                    ];

                    TrPurchaseNonDetails::create($dataDetail);
                }
            }

            foreach ($this->files as $file) {
                $filename = $file->store('/', 'purchase_non_disk');

                if ($filename) {
                    $dataFiles = [
                        'purchase_non_id' => $purchase->id,
                        'file' => $filename,
                    ];

                    TrPurchaseNonFiles::create($dataFiles);
                }
            }
        } else {
            $rules = [
                'supplier_id' => 'required',
                'reference' => '',
                'notes' => '',
                'subtotal' => '',
                'delivery_fee' => '',
                'discount' => '',
                'total' => '',
            ];

            $valid = $this->validate($rules);
            $valid['updated_by'] = Auth::user()->id;
            $purchase = TrPurchaseNon::find($this->set_id);
            $purchase->update($valid);

            TrPurchaseNonDetails::where('purchase_non_id', $this->set_id)->delete();
            foreach ($this->items as $key => $item) {
                if ($item['name'] != "") {
                    $dataDetail = [
                        'purchase_non_id' => $this->set_id,
                        'product_name' => $item['name'],
                        'unit_name' => $item['unit'],
                        'qty' => $item['qty'],
                        'rate' => $item['price'],
                        'amount' => $item['total'],
                    ];

                    TrPurchaseNonDetails::create($dataDetail);
                }
            }

            foreach ($this->files as $file) {
                $filename = $file->store('/', 'purchase_non_disk');

                if ($filename) {
                    $dataFiles = [
                        'purchase_non_id' => $purchase->id,
                        'file' => $filename,
                    ];

                    TrPurchaseNonFiles::create($dataFiles);
                }
            }

            $numberOrder = $purchase->number;
        }

        session()->flash('success', 'Saved ' . $numberOrder);
        return redirect()->to('/purchase/non-tax');
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
        $file = TrPurchaseNonFiles::find($this->set_id_file);
        $filePath = public_path('purchase_non_files/' . $file->file);

        if (file_exists($filePath)) {
            unset($filePath);
        }

        $file->delete();
        $this->dispatchBrowserEvent('close-modal');

        $this->purchaseFiles = TrPurchaseNonFiles::where('purchase_non_id', $this->set_id)->get();
        $this->set_id_file = null;
    }
}
