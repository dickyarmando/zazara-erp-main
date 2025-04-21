<div>
    @section('title', 'Receive Report')

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

    <div class="card mb-4">
        <div class="card-widget-separator-wrapper">
            <div class="card-body card-widget-separator">
                <div class="row gy-4 gy-sm-1">
                    <div class="col-sm-6 col-lg-3">
                        <div
                            class="d-flex justify-content-between align-items-center card-widget-2 border-end pb-4 pb-sm-0">
                            <div>
                                <h4 class="mb-0">{{ number_format($saless->total()) }}</h4>
                                <p class="mb-0">Invoices</p>
                            </div>
                            <div class="avatar me-lg-6">
                                <span class="avatar-initial rounded bg-label-secondary text-heading">
                                    <i class="bx bx-file bx-26px"></i>
                                </span>
                            </div>
                        </div>
                        <hr class="d-none d-sm-block d-lg-none">
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <div
                            class="d-flex justify-content-between align-items-center card-widget-1 border-end pb-4 pb-sm-0">
                            <div>
                                <h4 class="mb-0">Rp. {{ number_format($salesSummary->total_payment, 2) }}</h4>
                                <p class="mb-0">Total Amount</p>
                            </div>
                            <div class="avatar me-sm-6">
                                <span class="avatar-initial rounded bg-label-secondary text-heading">
                                    <i class="bx bx-dollar bx-26px"></i>
                                </span>
                            </div>
                        </div>
                        <hr class="d-none d-sm-block d-lg-none me-6">
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <div
                            class="d-flex justify-content-between align-items-center border-end pb-4 pb-sm-0 card-widget-3">
                            <div>
                                <h4 class="mb-0">Rp. {{ number_format($salesSummary->paid, 2) }}</h4>
                                <p class="mb-0">Paid</p>
                            </div>
                            <div class="avatar me-sm-6">
                                <span class="avatar-initial rounded bg-label-secondary text-heading">
                                    <i class="bx bx-check-double bx-26px"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="mb-0">Rp. {{ number_format($salesSummary->unpaid, 2) }}</h4>
                                <p class="mb-0">Unpaid</p>
                            </div>
                            <div class="avatar">
                                <span class="avatar-initial rounded bg-label-secondary text-heading">
                                    <i class="bx bx-error-circle bx-26px"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-action mb-4">
        <div class="card-header">
            <h5 class="card-action-title mb-0">Filter</h5>
            <div class="card-action-element">
                <ul class="list-inline mb-0">
                    <li class="list-inline-item">
                        <a href="javascript:void(0);" class="card-collapsible"><i
                                class="tf-icons bx bx-chevron-up"></i></a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="collapse show">
            <div class="card-body pt-0">
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Start Date</label>
                            <input type="date" wire:model="start_date" class="form-control" placeholder="Start Date">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">End Date</label>
                            <input type="date" wire:model="end_date" class="form-control" placeholder="End Date">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Search</label>
                            <input type="text" wire:model.debounce.500ms="searchKeyword" class="form-control"
                                placeholder="Search" maxlength="20">
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
                        <div class="d-md-flex justify-content-end">
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
                            <th class="sort" wire:click="sortOrder('invoice_number')">Invoice Number
                                {!! $sortLink !!}
                            </th>
                            <th class="sort" wire:click="sortOrder('number')">Sales Number
                                {!! $sortLink !!}
                            </th>
                            <th class="sort" wire:click="sortOrder('date')">Date {!! $sortLink !!}
                            </th>
                            <th class="sort" wire:click="sortOrder('customer_name')">Customer
                                {!! $sortLink !!}</th>
                            <th class="sort" wire:click="sortOrder('total')">Total {!! $sortLink !!}
                            </th>
                            <th class="sort" wire:click="sortOrder('payment')">Paid {!! $sortLink !!}
                            </th>
                            <th class="sort" wire:click="sortOrder('rest')">Rest {!! $sortLink !!}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($saless as $sales)
                            <tr>
                                <td class="text-center">
                                    {{ ($saless->currentPage() - 1) * $saless->perPage() + $loop->index + 1 }}
                                </td>
                                <td class="border-start text-center">{{ $sales->invoice_number }}</td>
                                <td class="border-start text-center">{{ $sales->number }}</td>
                                <td class="border-start text-center">{{ $sales->date }}</td>
                                <td class="border-start">{{ $sales->customer_name }}</td>
                                <td class="border-start text-right">{{ number_format($sales->total, 2) }}</td>
                                <td class="border-start text-right">{{ number_format($sales->payment, 2) }}</td>
                                @if ($sales->payment == 0)
                                    <td class="border-start text-right">{{ number_format($sales->total, 2) }}</td>
                                @else
                                    <td class="border-start text-right">{{ number_format($sales->rest, 2) }}</td>
                                @endif
                            </tr>
                        @endforeach

                        @if ($saless->count() <= 0)
                            <tr>
                                <td colspan="7" class="text-center">No data..</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="panel-footer">
                {{ $saless->links('admin.custom-pagination-new') }}
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
