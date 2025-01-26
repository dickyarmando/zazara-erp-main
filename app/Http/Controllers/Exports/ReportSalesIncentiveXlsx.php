<?php

namespace App\Http\Controllers\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ReportSalesIncentiveXlsx implements FromView
{
    protected $data;

    public function __construct(array $data) {
        $this->data = $data;
    }

    public function view(): View
    {
        return view('livewire.exports.sales-incentive-report-xslx', [
            'incentiveSales' => $this->data['incentiveSales'],
            'summaryIncentiveSales' => $this->data['summaryIncentiveSales'],
            'incentiveAmount' => $this->data['incentiveAmount'],
            'incentiveDetails' => $this->data['incentiveDetails'],
            'invoice_start_date' => $this->data['invoice_start_date'],
            'invoice_end_date' => $this->data['invoice_end_date']
        ]);
    }
}
