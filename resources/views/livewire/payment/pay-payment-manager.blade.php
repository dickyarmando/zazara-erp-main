<div>
    @section('title', 'Pay Payments')

    <div class="d-md-flex justify-content-between">
        <h2 class="mb-3"><span class="text-muted fw-light">Data @yield('title')</span></h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="javascript:void(0);">Payments</a>
                </li>
                <li class="breadcrumb-item active">@yield('title')</li>
            </ol>
        </nav>
    </div>

    <x-flash-alert />

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
                        <input type="text" class="form-control w-100" placeholder="Search"
                            wire:model.debounce.500ms="searchKeyword">
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
                            <th class="sort" wire:click="sortOrder('total')">Total {!! $sortLink !!}
                            </th>
                            <th class="sort" wire:click="sortOrder('payment')">Paid {!! $sortLink !!}
                            </th>
                            <th class="sort" wire:click="sortOrder('rest')">Rest {!! $sortLink !!}
                            </th>
                            <th class="w-px-150">Action</th>
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
                                <td class="border-start text-right">{{ number_format($purchase->total, 2) }}</td>
                                <td class="border-start text-right">{{ number_format($purchase->payment, 2) }}</td>
                                @if ($purchase->payment == 0)
                                    <td class="border-start text-right">{{ number_format($purchase->total, 2) }}</td>
                                @else
                                    <td class="border-start text-right">{{ number_format($purchase->rest, 2) }}</td>
                                @endif
                                <td class="border-start text-center">
                                    <button type="button"
                                        wire:click="view('{{ $purchase->id }}','{{ $purchase->type }}')"
                                        class="btn btn-xs btn-success" title="Open Data"><span
                                            class="bx bx-folder-open"></span></button>
                                </td>
                            </tr>
                        @endforeach

                        @if ($purchases->count() <= 0)
                            <tr>
                                <td colspan="8" class="text-center">No data..</td>
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
        <script></script>
    @endpush
</div>
