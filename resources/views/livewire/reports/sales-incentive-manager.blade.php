<div>
    @section('title', 'Sales Incentive')

    <div class="d-md-flex justify-content-between">
        <h2 class="mb-3"><span class="text-muted fw-light">Data @yield('title')</span></h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="javascript:void(0);">Reports</a>
                </li>
                <li class="breadcrumb-item active">@yield('title')</li>
            </ol>
        </nav>
    </div>

    <x-flash-alert />

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-widget-separator-wrapper">
                    <div class="card-body">
                        <div class="row gy-4 gy-sm-1">
                            <div class="col-sm-12 col-lg-12 mb-4">
                                <div
                                    class="d-flex justify-content-between align-items-center card-widget-2 pb-4 pb-sm-0">
                                    <div>
                                        <h4 class="mb-0">
                                            Rp. {{ number_format($incentiveDetails->target_amount, 0) }}
                                        </h4>
                                        <p class="mb-0">Target</p>
                                    </div>
                                    <div class="avatar me-lg-6">
                                        <span class="avatar-initial rounded bg-label-secondary text-heading">
                                            <i class="bx bx-file bx-26px"></i>
                                        </span>
                                    </div>
                                </div>
                                <hr class="d-none d-sm-block d-lg-none">
                            </div>
                        </div>

                        <div class="row gy-4 gy-sm-1">
                            <div class="col-sm-12 col-lg-12 mb-4">
                                <div
                                    class="d-flex justify-content-between align-items-center card-widget-2 pb-4 pb-sm-0">
                                    <div>
                                        <h4 class="mb-0 d-flex justify-content-between">Rp. {{ number_format($summaryIncentiveSales, 0) }}</h4>
                                        <p class="mb-0">Total</p>
                                    </div>
                                    <div class="avatar me-lg-6">
                                        <span class="avatar-initial rounded bg-label-secondary text-heading">
                                            <i class="bx bx-file bx-26px"></i>
                                        </span>
                                    </div>
                                </div>
                                <hr class="d-none d-sm-block d-lg-none">
                            </div>
                        </div>

                        <div class="col-sm-12 col-lg-12">
                            <div
                                class="d-flex justify-content-between align-items-center card-widget-2 pb-4 pb-sm-0">
                                <div>
                                    <h4 class="mb-0">Rp. {{ number_format($incentiveAmount, 0) }}</h4>
                                    <p class="mb-0">Incentive</p>
                                </div>
                                <div class="avatar me-lg-6">
                                    <span class="avatar-initial rounded bg-label-secondary text-heading">
                                        <i class="bx bx-file bx-26px"></i>
                                    </span>
                                </div>
                            </div>
                            <hr class="d-none d-sm-block d-lg-none">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Filter</h5>
                    <div class="d-flex align-items-center row gap-6 gap-md-0 g-md-6">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Invoice Start Date</label>
                                    <input type="date" wire:model="invoice_start_date" class="form-control" placeholder="Invoice Start Date">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Invoice End Date</label>
                                    <input type="date" wire:model="invoice_end_date" class="form-control" placeholder="Invoice End Date">
                                </div>
                            </div>
                            @if($filterSales == TRUE)
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Sales Username</label>
                                    <option value="">Pilih Sales</option>
                                    <select class="form-control" wire:model="sales_username" placeholder="Sales Username">
                                        @foreach ($listSales as $sales)
                                            <option value="{{ $sales->username }}">
                                                {{ $sales->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-offset-1 col-md-12">
        <div class="panel">
            <div class="panel-heading">
                <div class="row">
                    <div class="col col-sm-4 col-xs-12 d-flex">
                        <select class="form-select shadow-sm me-2 w-px-75" wire:model="perPage">
                            @foreach ([10, 25, 50, 100] as $val)
                                <option value="{{ $val }}" @if ($val == $perPage) selected @endif>
                                    {{ $val }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-8 col-xs-12 text-right">
                        <div class="d-md-flex justify-content-end gap-3">
                            <button class="btn btn-success btn-sm" wire:click="exportExcel" target="_blank"><i
                                    class='bx bxs-file-export me-2'></i>Download</button>

                            <button class="btn btn-success btn-sm" wire:click="printTable" target="_blank"><i
                                    class="bx bxs-printer me-2"></i>Print</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-body table-responsive position-relative">
                <div wire:loading class="position-absolute fs-1 top-50 start-50 z-3 text-info">
                    <i class="fa fa-spin fa-spinner"></i>
                </div>
                <table class="table">
                    <thead>
                        <tr>
                            <th class="w-px-75">No</th>
                            <th class="sort" wire:click="sortOrder('invoice_date')">Invoice Date {!! $sortLink !!}</th>
                            <th class="sort" wire:click="sortOrder('customer_name')">Customer {!! $sortLink !!}</th>
                            <th class="sort" wire:click="sortOrder('invoice_no')">Invoice No {!! $sortLink !!}</th>
                            <th class="sort no-wrap" wire:click="sortOrder('total_capital_price')">Capital Price {!! $sortLink !!}</th>
                            <th class="sort no-wrap" wire:click="sortOrder('total_selling_price')">Selling Price {!! $sortLink !!}</th>
                            <th class="sort" wire:click="sortOrder('total_margin')">Margin {!! $sortLink !!}</th>
                            <th class="sort">Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($incentiveSales as $incentiveDetail)
                            <tr>
                                <td class="text-center">
                                    {{ ($incentiveSales->currentPage() - 1) * $incentiveSales->perPage() + $loop->index + 1 }}
                                </td>
                                <td class="border-start text-center no-wrap">{{ $incentiveDetail->invoice_date }}</td>
                                <td class="border-start text-center no-wrap">{{ $incentiveDetail->customer_name }}</td>
                                <td class="border-start text-center no-wrap">{{ $incentiveDetail->invoice_no }}</td>
                                <td class="border-start">
                                    <div class="d-flex justify-content-between">
                                        <span>Rp</span>
                                        <span>{{ number_format($incentiveDetail->total_capital_price, 0) }}</span> 
                                    </td>
                                    </div>
                                <td class="border-start">
                                    <div class="d-flex justify-content-between">
                                        <span>Rp</span>
                                        <span>{{ number_format($incentiveDetail->total_selling_price, 0) }}</span>
                                    </td>
                                    </div>
                                <td class="border-start">
                                    <div class="d-flex justify-content-between">
                                        <span>Rp</span>
                                        <span>{{ number_format($incentiveDetail->total_margin, 0) }}</span>
                                    </td>
                                    </div>
                                <td class="border-start text-right no-wrap">{{ $incentiveDetail->notes }}</td>
                            </tr>
                        @endforeach

                        @if (count($incentiveSales) <= 0 || $incentiveSales->count() <= 0)
                            <tr>
                                <td colspan="10" class="text-center">No data..</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="panel-footer">
            @if (count($incentiveSales) > 0)
                {{ $incentiveSales->links('admin.custom-pagination-new') }}
            @endif
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            window.addEventListener('openTab', event => {
                window.open(event.detail.url, '_blank');
            });
        </script>
    @endpush
</div>
