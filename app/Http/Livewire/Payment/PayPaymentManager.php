<?php

namespace App\Http\Livewire\Payment;

use App\Models\TrPayments;
use App\Models\TrPurchase;
use App\Models\TrPurchaseNon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class PayPaymentManager extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $perPage = 10;
    public $sortColumn = "date";
    public $sortOrder = "desc";
    public $sortLink = '<i class="sorticon fa-solid fa-caret-up"></i>';
    public $searchKeyword = '';
    public $roleFilter = '';
    public $set_id;

    public $selected = [];
    public $selectedN = [];
    public $selectAll = false;
    public $purchasesPayMultiple = [];

    public function render()
    {
        $purchaseTax = TrPurchase::leftJoin('ms_suppliers', 'ms_suppliers.id', '=', 'tr_purchase.supplier_id')
            ->select('tr_purchase.id', 'tr_purchase.number', 'tr_purchase.date', 'tr_purchase.supplier_id', 'ms_suppliers.company_name as supplier_name', 'tr_purchase.reference', 'tr_purchase.total', 'tr_purchase.payment', 'tr_purchase.rest', 'tr_purchase.notes', 'tr_purchase.is_payed', 'tr_purchase.is_status')
            ->addSelect(DB::raw('"Tax" as type'))
            ->whereNotNull('tr_purchase.approved_at')
            ->where('is_payed', '0');

        $purchaseNonTax = TrPurchaseNon::leftJoin('ms_suppliers', 'ms_suppliers.id', '=', 'tr_purchase_non.supplier_id')
            ->select('tr_purchase_non.id', 'tr_purchase_non.number', 'tr_purchase_non.date', 'tr_purchase_non.supplier_id', 'ms_suppliers.company_name as supplier_name', 'tr_purchase_non.reference', 'tr_purchase_non.total', 'tr_purchase_non.payment', 'tr_purchase_non.rest', 'tr_purchase_non.notes', 'tr_purchase_non.is_payed', 'tr_purchase_non.is_status')
            ->addSelect(DB::raw('"Non" as type'))
            ->whereNotNull('tr_purchase_non.approved_at')
            ->where('is_payed', '0');

        if (!empty($this->searchKeyword)) {
            $purchaseTax->orWhere('number', 'like', "%" . $this->searchKeyword . "%")->whereNotNull('tr_purchase.approved_at')->where('is_payed', '0');
            $purchaseTax->orWhere('ms_suppliers.company_name', 'like', "%" . $this->searchKeyword . "%")->whereNotNull('tr_purchase.approved_at')->where('is_payed', '0');
            $purchaseTax->orWhere('total', 'like', "%" . $this->searchKeyword . "%")->whereNotNull('tr_purchase.approved_at')->where('is_payed', '0');
            $purchaseTax->orWhere('payment', 'like', "%" . $this->searchKeyword . "%")->whereNotNull('tr_purchase.approved_at')->where('is_payed', '0');
            $purchaseTax->orWhere('rest', 'like', "%" . $this->searchKeyword . "%")->whereNotNull('tr_purchase.approved_at')->where('is_payed', '0');

            $purchaseNonTax->orWhere('number', 'like', "%" . $this->searchKeyword . "%")->whereNotNull('tr_purchase_non.approved_at')->where('is_payed', '0');
            $purchaseNonTax->orWhere('ms_suppliers.company_name', 'like', "%" . $this->searchKeyword . "%")->whereNotNull('tr_purchase_non.approved_at')->where('is_payed', '0');
            $purchaseNonTax->orWhere('total', 'like', "%" . $this->searchKeyword . "%")->whereNotNull('tr_purchase_non.approved_at')->where('is_payed', '0');
            $purchaseNonTax->orWhere('payment', 'like', "%" . $this->searchKeyword . "%")->whereNotNull('tr_purchase_non.approved_at')->where('is_payed', '0');
            $purchaseNonTax->orWhere('rest', 'like', "%" . $this->searchKeyword . "%")->whereNotNull('tr_purchase_non.approved_at')->where('is_payed', '0');
        }

        $purchases = $purchaseTax->union($purchaseNonTax)->orderBy($this->sortColumn, $this->sortOrder);
        $purchases = $purchases->paginate($this->perPage);

        return view('livewire.payment.pay-payment-manager', compact('purchases'));
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

    public function view($id, $type)
    {
        return redirect()->to('/pay/view/' . $id . '/' . $type);
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selected = $this->getAllPurchaseIds();
            $this->selectedN = $this->getAllPurchaseIdsN();
        } else {
            $this->selected = [];
            $this->selectedN = [];
        }
    }

    public function updatedSelected()
    {
        if (count($this->selected) + count($this->selectedN) === $this->getAllPurchaseIds()->count() + $this->getAllPurchaseIdsN()->count()) {
            $this->selectAll = true;
            $this->dispatchBrowserEvent('checkall-indeterminate-false');
            $this->dispatchBrowserEvent('checkall-checked');
        } elseif (count($this->selected) + count($this->selectedN) === 0) {
            $this->selectAll = false;
            $this->dispatchBrowserEvent('checkall-checked-false');
            $this->dispatchBrowserEvent('checkall-indeterminate-false');
        } else {
            $this->dispatchBrowserEvent('checkall-indeterminate');
        }
    }

    public function updatedSelectedN()
    {
        if (count($this->selected) + count($this->selectedN) === $this->getAllPurchaseIds()->count() + $this->getAllPurchaseIdsN()->count()) {
            $this->selectAll = true;
            $this->dispatchBrowserEvent('checkall-indeterminate-false');
            $this->dispatchBrowserEvent('checkall-checked');
        } elseif (count($this->selected) + count($this->selectedN) === 0) {
            $this->selectAll = false;
            $this->dispatchBrowserEvent('checkall-indeterminate-false');
            $this->dispatchBrowserEvent('checkall-checked-false');
        } else {
            $this->dispatchBrowserEvent('checkall-indeterminate');
        }
    }

    private function getAllPurchaseIds()
    {
        $purchaseTax = TrPurchase::whereNotNull('approved_at')->where('is_payed', '0')->pluck('id');
        return $purchaseTax;
    }

    private function getAllPurchaseIdsN()
    {
        $purchaseNonTax = TrPurchaseNon::whereNotNull('approved_at')->where('is_payed', '0')->pluck('id');
        return $purchaseNonTax;
    }

    public function paymentMultiple()
    {
        if (count($this->selected) <= 0 && count($this->selectedN) <= 0) {
            session()->flash('error', 'Please select at least one purchase');
            $this->closeModal();
        }

        $purchases = TrPurchase::whereIn('id', $this->selected)
            ->select('id', 'number', 'total', 'payment', 'rest', 'rest as amount')
            ->addSelect(DB::raw('"Tax" as type'))
            ->addSelect(DB::raw('CURDATE() as date'))
            ->addSelect(DB::raw('"1" as payment_method_id'))
            ->addSelect(DB::raw('"" as notes'))
            ->get()
            ->toArray();
        $purchasesN = TrPurchaseNon::whereIn('id', $this->selectedN)
            ->select('id', 'number', 'total', 'payment', 'rest', 'rest as amount')
            ->addSelect(DB::raw('"Non" as type'))
            ->addSelect(DB::raw('CURDATE() as date'))
            ->addSelect(DB::raw('"1" as payment_method_id'))
            ->addSelect(DB::raw('"" as notes'))
            ->get()
            ->toArray();

        $this->purchasesPayMultiple = array_merge($purchases, $purchasesN);
    }

    public function store()
    {
        foreach ($this->purchasesPayMultiple as $key => $val) {

            $purchaseType = '1';
            if ($val['type'] == 'Non') {
                $purchaseType = '2';
            }

            $dataPayment = [
                'purchase_id' => $val['id'],
                'purchase_type' => $purchaseType,
                'date' => $val['date'],
                'payment_method_id' => $val['payment_method_id'],
                'amount' => $val['amount'],
                'notes' => $val['notes'],
                'created_by' => Auth::user()->id,
                'updated_by' => Auth::user()->id,
            ];

            TrPayments::create($dataPayment);

            $payment = $val['payment'] + $val['amount'];
            $balance = $val['total'] - $payment;

            $dataPurchase = [
                'payment' => $payment,
                'rest' => $balance,
                'is_payed' => '0',
                'updated_by' => Auth::user()->id,
            ];

            if ($balance <= 0) {
                $dataPurchase['is_payed'] = '1';
            }

            if ($val['type'] == 'Tax') {
                TrPurchase::find($val['id'])->update($dataPurchase);
            } else if ($val['type'] == 'Non') {
                TrPurchaseNon::find($val['id'])->update($dataPurchase);
            }
        }

        $this->formReset();
        session()->flash('success', 'Saved');
    }

    public function closeModal()
    {
        $this->resetErrorBag();
        $this->resetValidation();
        $this->dispatchBrowserEvent('close-modal');
    }

    public function formReset()
    {
        $this->selected = [];
        $this->selectedN = [];
        $this->selectAll = false;
        $this->purchasesPayMultiple = [];

        $this->closeModal();
        $this->dispatchBrowserEvent('checkall-indeterminate-false');
    }
}
