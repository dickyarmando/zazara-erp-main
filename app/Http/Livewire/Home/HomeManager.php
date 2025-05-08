<?php

namespace App\Http\Livewire\Home;

use App\Models\TrSales;
use App\Models\TrSalesNon;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class HomeManager extends Component
{
    public $top_customer_year;
    public $credit_customer_year;

    public function mount()
    {
        $now = Carbon::now();
        $this->top_customer_year = $now->year;
        $this->credit_customer_year = $now->year;
    }

    public function render()
    {
        $monthlySales = $this->getMonthlySales();
        $yearlySales = $this->getYearlySales();
        $topCustomer = $this->getTopCustomer();
        $creditCustomer = $this->getCreditCustomer();
        $allSalesYear = $this->getAllSalesYear();

        return view('livewire.home.home', [
            'monthlySales' => $monthlySales,
            'yearlySales' => $yearlySales,
            'topCustomer' => $topCustomer,
            'creditCustomer' => $creditCustomer,
            'allSalesYear' => $allSalesYear,
        ]);
    }

    private function getMonthlySales()
    {
        $querySales = TrSales::select(
            DB::raw('MONTH(tr_sales.date) AS sales_month'),
            DB::raw('SUM(tr_sales.total) AS total'),
        )
        ->where('tr_sales.is_invoice', '=', 1)
        ->groupBy(DB::raw('MONTH(tr_sales.date)'));

        $querySalesNon = TrSalesNon::select(
            DB::raw('MONTH(tr_sales_non.date) AS sales_month'),
            DB::raw('SUM(tr_sales_non.total) AS total'),
        )
        ->where('tr_sales_non.is_invoice', '=', 1)
        ->groupBy(DB::raw('MONTH(tr_sales_non.date)'));

        $unionQuery = $querySales->union($querySalesNon);

        $results = DB::table(DB::raw("({$unionQuery->toSql()}) as combined"))
            ->mergeBindings($unionQuery->getQuery())
            ->select('sales_month', DB::raw('SUM(total) as total'))
            ->groupBy('sales_month');

        return $results->get();
    }

    private function getYearlySales()
    {
        $querySales = TrSales::select(
            DB::raw('YEAR(tr_sales.date) AS sales_year'),
            DB::raw('SUM(tr_sales.total) AS total'),
        )
        ->where('tr_sales.is_invoice', '=', 1)
        ->groupBy(DB::raw('YEAR(tr_sales.date)'));

        $querySalesNon = TrSalesNon::select(
            DB::raw('YEAR(tr_sales_non.date) AS sales_year'),
            DB::raw('SUM(tr_sales_non.total) AS total'),
        )
        ->where('tr_sales_non.is_invoice', '=', 1)
        ->groupBy(DB::raw('YEAR(tr_sales_non.date)'));

        $unionQuery = $querySales->union($querySalesNon);

        $results = DB::table(DB::raw("({$unionQuery->toSql()}) as combined"))
            ->mergeBindings($unionQuery->getQuery())
            ->select('sales_year', DB::raw('SUM(total) as total'))
            ->groupBy('sales_year');

        return $results->get();
    }

    private function getTopCustomer($limit = 10)
    {
        $querySales = TrSales::select(
            DB::raw('ms_customers.company_name AS customer'),
            DB::raw('SUM(tr_sales.total) AS total'),
        )
        ->join('ms_customers', 'ms_customers.id', '=', 'tr_sales.customer_id')
        ->where('tr_sales.is_invoice', '=', 1)
        ->where(DB::raw('YEAR(tr_sales.date)'), '=', $this->top_customer_year)
        ->groupBy('ms_customers.company_name');

        $querySalesNon = TrSalesNon::select(
            DB::raw('ms_customers.company_name AS customer'),
            DB::raw('SUM(tr_sales_non.total) AS total'),
        )
        ->join('ms_customers', 'ms_customers.id', '=', 'tr_sales_non.customer_id')
        ->where('tr_sales_non.is_invoice', '=', 1)
        ->where(DB::raw('YEAR(tr_sales_non.date)'), '=', $this->top_customer_year)
        ->groupBy('ms_customers.company_name');

        $unionQuery = $querySales->union($querySalesNon);

        $results = DB::table(DB::raw("({$unionQuery->toSql()}) as combined"))
            ->mergeBindings($unionQuery->getQuery())
            ->select('customer', DB::raw('SUM(total) as total'))
            ->groupBy('customer')
            ->orderBy('total', 'desc')
            ->limit($limit);

        return $results->get();
    }

    private function getCreditCustomer()
    {
        $querySales = TrSales::select(
            DB::raw('ms_customers.company_name AS customer'),
            DB::raw('SUM(tr_sales.rest) AS rest'),
        )
        ->join('ms_customers', 'ms_customers.id', '=', 'tr_sales.customer_id')
        ->where('tr_sales.is_invoice', '=', 1)
        ->where('tr_sales.rest', '<', 0)
        ->where(DB::raw('YEAR(tr_sales.date)'), '=', $this->credit_customer_year)
        ->groupBy('ms_customers.company_name');

        $querySalesNon = TrSalesNon::select(
            DB::raw('ms_customers.company_name AS customer'),
            DB::raw('SUM(tr_sales_non.rest) AS rest'),
        )
        ->join('ms_customers', 'ms_customers.id', '=', 'tr_sales_non.customer_id')
        ->where('tr_sales_non.is_invoice', '=', 1)
        ->where('tr_sales_non.rest', '<', 0)
        ->where(DB::raw('YEAR(tr_sales_non.date)'), '=', $this->credit_customer_year)
        ->groupBy('ms_customers.company_name');

        $unionQuery = $querySales->union($querySalesNon);

        $results = DB::table(DB::raw("({$unionQuery->toSql()}) as combined"))
            ->mergeBindings($unionQuery->getQuery())
            ->select('customer', DB::raw('SUM(rest) as rest'))
            ->groupBy('customer')
            ->orderBy('rest');

        return $results->get();
    }

    private function getAllSalesYear()
    {
        $querySales = TrSales::select(
            DB::raw('YEAR(tr_sales.date) AS year')
        )
        ->groupBy('year');

        $querySalesNon = TrSalesNon::select(
            DB::raw('YEAR(tr_sales_non.date) AS year'),
        )
        ->groupBy('year');

        $unionQuery = $querySales->union($querySalesNon)->orderBy('year', 'desc');

        return $unionQuery->get();
    }
}
