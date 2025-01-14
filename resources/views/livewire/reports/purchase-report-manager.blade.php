<div>
    @section('title', 'Purchase Reports')

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
                            <label class="form-label">Purchase Number</label>
                            <input type="text" wire:model="number" class="form-control" placeholder="Purchase Number"
                                maxlength="20">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Supplier</label>
                            <input type="text" wire:model="supplier" class="form-control" placeholder="Supplier"
                                maxlength="20">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Product</label>
                            <input type="text" wire:model="product" class="form-control" placeholder="Product"
                                maxlength="20">
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
                            <th class="sort" wire:click="sortOrder('number')">Purchase Number
                                {!! $sortLink !!}
                            </th>
                            <th class="sort" wire:click="sortOrder('date')">Date {!! $sortLink !!}
                            </th>
                            <th class="sort" wire:click="sortOrder('supplier_name')">Supplier
                                {!! $sortLink !!}</th>
                            <th class="sort" wire:click="sortOrder('product_name')">Product {!! $sortLink !!}
                            </th>
                            <th class="sort no-wrap" wire:click="sortOrder('unit_name')">UoM {!! $sortLink !!}
                            </th>
                            <th class="sort no-wrap" wire:click="sortOrder('qty')">Qty {!! $sortLink !!}
                            </th>
                            <th class="sort" wire:click="sortOrder('rate')">Rate {!! $sortLink !!}
                            </th>
                            <th class="sort">Status</th>
                            <th class="sort">Payment</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($purchases as $purchase)
                            <tr>
                                <td class="text-center">
                                    {{ ($purchases->currentPage() - 1) * $purchases->perPage() + $loop->index + 1 }}
                                </td>
                                <td class="border-start text-center">{{ $purchase->number }}</td>
                                <td class="border-start text-center">{{ $purchase->date }}</td>
                                <td class="border-start">{{ $purchase->supplier_name }}</td>
                                <td class="border-start">{{ $purchase->product_name }}</td>
                                <td class="border-start">{{ $purchase->unit_name }}</td>
                                <td class="border-start no-wrap">{{ number_format($purchase->qty, 0) }}</td>
                                <td class="border-start text-right no-wrap">{{ number_format($purchase->rate, 2) }}
                                </td>
                                <td class="border-start text-center no-wrap">
                                    @if ($purchase->is_status == '1')
                                        @if (isset($purchase->approved_at))
                                            <span class="badge bg-label-success" text-capitalized> Approved </span>
                                        @else
                                            <span class="badge bg-label-warning" text-capitalized> Waiting Approve
                                            </span>
                                        @endif
                                    @else
                                        <span class="badge bg-label-danger" text-capitalized> Cancelled </span>
                                    @endif
                                </td>
                                <td class="border-start text-center no-wrap">
                                    @if ($purchase->is_payed == '1')
                                        <span class="badge bg-label-success" text-capitalized> Paid </span>
                                    @else
                                        @if ($purchase->payment > 0)
                                            <span class="badge bg-label-warning" text-capitalized> being paid </span>
                                        @else
                                            <span class="badge bg-label-danger" text-capitalized> Unpaid </span>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @endforeach

                        @if ($purchases->count() <= 0)
                            <tr>
                                <td colspan="10" class="text-center">No data..</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="panel-footer">
                {{ $purchases->links('admin.custom-pagination-new') }}
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
