<?php

namespace App\Http\Livewire\Reports;

use App\Http\Controllers\Exports\ReportSalesIncentiveXlsx;
use App\Models\MsInsentifSales;
use App\Models\PrmConfig;
use App\Models\TrInvoice;
use App\Models\TrInvoicesNon;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use stdClass;

class SalesIncentiveManager extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $perPage = 10;
    public $sortColumn = "invoice_no";
    public $sortOrder = "desc";
    public $sortLink = '<i class="sorticon fa-solid fa-caret-up"></i>';
    public $filterSales = FALSE;

    public $invoice_start_date;
    public $invoice_end_date;
    public $sales_username;
    public $sales_role_id;
    public $user;

    public function mount()
    {
        $this->invoice_start_date = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->invoice_end_date = Carbon::now()->format('Y-m-d');
        
        $this->user = Auth()->user();
        $querySalesRoleId = PrmConfig::where('is_status', '1')->where('code', 'sri')->value('value');
        $this->sales_role_id = $querySalesRoleId ?: null;

        $this->sales_username = $this->user->username;
        $this->filterSales = $this->user->role_id != (int) $this->sales_role_id;
    }

    public function render()
    {
        $listSales = $this->listSales();

        if(!$this->sales_username){
            $incentiveDetails = new stdClass();
            $incentiveDetails->target_amount = 0;
            $incentiveDetails->up = 0;
            $incentiveDetails->down = 0;
            $incentiveDetails->sales_name = null;

            $incentiveData = [
                "incentiveSales" => [],
                "summaryIncentiveSales" => 0,
                "incentiveAmount" => 0,
                "incentiveDetails" => $incentiveDetails,
            ];
        }else{
            $incentiveData = $this->calculateIncentives();
        }

        return view('livewire.reports.sales-incentive-manager', [
            'incentiveSales' => $incentiveData['incentiveSales'],
            'summaryIncentiveSales' => $incentiveData['summaryIncentiveSales'],
            'incentiveAmount' => $incentiveData['incentiveAmount'],
            'incentiveDetails' => $incentiveData['incentiveDetails'],
            'listSales' => $listSales,
        ]);
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

    private function listSales()
    {
        $self_user = new stdClass();
        $self_user->name = $this->user->name;
        $self_user->username = $this->user->username;

        $query = User::where('is_status', '1')
            ->where('role_id', $this->sales_role_id)
            ->select('username', 'name');

        return array_merge(array($self_user), $query->get()->all());
    }

    private function calculateIncentives()
    {
        $queryInvoices = TrInvoice::select(
            'tr_invoices.date AS invoice_date',
            'tr_invoices.number AS invoice_no',
            'ms_customers.name AS customer_name',
            'tr_invoices.notes',
            DB::raw('COALESCE(tr_invoices.total - tr_invoices.ppn_amount, 0) AS total_selling_price'),
            DB::raw('COALESCE(SUM(combined_purchases.total), 0) AS total_capital_price'),
            DB::raw('COALESCE((tr_invoices.total - tr_invoices.ppn_amount) - SUM(combined_purchases.total), 0) AS total_margin')
        )
            ->join('tr_sales', 'tr_sales.id', '=', 'tr_invoices.sales_id')
            ->join('ms_customers', 'ms_customers.id', '=', 'tr_sales.customer_id')
            ->join('users', 'users.id', '=', 'tr_sales.sales_id')
            ->leftJoinSub(
                DB::table(function ($unionQuery) {
                    $unionQuery->from('tr_purchase')
                        ->select('reference', DB::raw('COALESCE((total - ppn_amount), 0) AS total'))
                        ->where('is_status', 1)
                        ->whereNotNull('approved_at')
                        ->unionAll(
                            DB::table('tr_purchase_non')
                                ->select('reference', 'total')
                                ->where('is_status', 1)
                                ->whereNotNull('approved_at')
                        );
                }, 'combined_purchases')
                ->select('reference', DB::raw('SUM(total) AS total'))
                ->groupBy('reference'),
                'combined_purchases', 'combined_purchases.reference', '=', 'tr_sales.number'
            )
            ->where('tr_invoices.is_status', 1)
            ->where('tr_invoices.approved_at', '!=', null)
            ->where('users.username', $this->sales_username)
            ->whereBetween('tr_invoices.date', [$this->invoice_start_date, $this->invoice_end_date])
            ->groupBy('tr_invoices.number', 'tr_invoices.date', 'tr_invoices.total', 'tr_invoices.ppn_amount', 'ms_customers.name', 'tr_invoices.notes')
            ->havingRaw('COALESCE(tr_invoices.total - SUM(combined_purchases.total), 0) > 0');

        $queryInvoicesNon = TrInvoicesNon::select(
            'tr_invoices_nons.date AS invoice_date',
            'tr_invoices_nons.number AS invoice_no',
            'ms_customers.name AS customer_name',
            'tr_invoices_nons.notes',
            DB::raw('COALESCE(tr_invoices_nons.total, 0) AS total_selling_price'),
            DB::raw('COALESCE(SUM(combined_purchases.total), 0) AS total_capital_price'),
            DB::raw('COALESCE(tr_invoices_nons.total - SUM(combined_purchases.total), 0) AS total_margin')
        )
            ->join('tr_sales_non', 'tr_sales_non.id', '=', 'tr_invoices_nons.sales_non_id')
            ->join('ms_customers', 'ms_customers.id', '=', 'tr_sales_non.customer_id')
            ->join('users', 'users.id', '=', 'tr_sales_non.sales_id')
            ->leftJoinSub(
                DB::table(function ($unionQuery) {
                    $unionQuery->from('tr_purchase')
                        ->select('reference', DB::raw('COALESCE((total - ppn_amount), 0) AS total'))
                        ->where('is_status', 1)
                        ->whereNotNull('approved_at')
                        ->unionAll(
                            DB::table('tr_purchase_non')
                                ->select('reference', 'total')
                                ->where('is_status', 1)
                                ->whereNotNull('approved_at')
                        );
                }, 'combined_purchases')
                ->select('reference', DB::raw('SUM(total) AS total'))
                ->groupBy('reference'),
                'combined_purchases', 'combined_purchases.reference', '=', 'tr_sales_non.number'
            )
            ->where('tr_invoices_nons.is_status', 1)
            ->where('tr_invoices_nons.approved_at', '!=', null)
            ->where('users.username', '=', $this->sales_username)
            ->whereBetween('tr_invoices_nons.date', [$this->invoice_start_date, $this->invoice_end_date])
            ->groupBy('tr_invoices_nons.number', 'tr_invoices_nons.date', 'tr_invoices_nons.total', 'ms_customers.name', 'tr_invoices_nons.notes')
            ->havingRaw('COALESCE(tr_invoices_nons.total - SUM(combined_purchases.total), 0) > 0');
        
        $queryInvoices->union($queryInvoicesNon)->orderBy($this->sortColumn, $this->sortOrder);

        $incentiveSales = $queryInvoices->paginate($this->perPage);

        $summary = $queryInvoices->get()->reduce(function ($carry, $item) {
            $carry['totalSellingPrice'] += $item->total_selling_price;
            $carry['totalCapitalPrice'] += $item->total_capital_price;
            return $carry;
        }, ['totalSellingPrice' => 0, 'totalCapitalPrice' => 0]);

        $summaryIncentiveSales = $summary['totalSellingPrice'] - $summary['totalCapitalPrice'];

        $incentiveDetails = MsInsentifSales::select('target_amount', 'up', 'down', 'users.name as sales_name')
            ->join('users', 'users.id', '=', 'ms_insentif_sales.user_id')
            ->where('users.username', '=', $this->sales_username)
            ->where('ms_insentif_sales.is_status', '=', '1')
            ->first();

        if($incentiveDetails){
            $incentiveAmount = $summaryIncentiveSales >= $incentiveDetails->target_amount
                ? $summaryIncentiveSales * ($incentiveDetails->up / 100)
                : $summaryIncentiveSales * ($incentiveDetails->down / 100);
        }else{
            $incentiveDetails = new stdClass();
            $incentiveDetails->target_amount = 0;
            $incentiveDetails->up = 0;
            $incentiveDetails->down = 0;
            $queryUser = User::where('username', $this->sales_username)->value('name');
            $incentiveDetails->sales_name = $queryUser ?: null;
            $incentiveAmount = 0;
        }

        return compact('incentiveSales', 'summaryIncentiveSales', 'incentiveAmount', 'incentiveDetails');
    }

    public function printTable()
    {
        $responseData = [
            'isd' => $this->invoice_start_date,
            'ied' => $this->invoice_end_date,
            'su' => $this->sales_username,
        ];

        $url = route('print.reports.sales.incentive', $responseData);
        $this->dispatchBrowserEvent('openTab', ['url' => $url]);
    }

    public function exportExcel()
    {
        if(!$this->sales_username){
            $incentiveDetails = new stdClass();
            $incentiveDetails->target_amount = 0;
            $incentiveDetails->up = 0;
            $incentiveDetails->down = 0;
            $incentiveDetails->sales_name = null;

            $incentiveData = [
                "incentiveSales" => [],
                "summaryIncentiveSales" => 0,
                "incentiveAmount" => 0,
                "incentiveDetails" => $incentiveDetails,
            ];
        }else{
            $incentiveData = $this->calculateIncentives();
        }

        return Excel::download(new ReportSalesIncentiveXlsx([
            'incentiveSales' => $incentiveData['incentiveSales'],
            'summaryIncentiveSales' => $incentiveData['summaryIncentiveSales'],
            'incentiveAmount' => $incentiveData['incentiveAmount'],
            'incentiveDetails' => $incentiveData['incentiveDetails'],
            'invoice_start_date' => $this->invoice_start_date,
            'invoice_end_date' => $this->invoice_end_date
        ]), 'report-sales-incentive.xlsx');
    }
}
