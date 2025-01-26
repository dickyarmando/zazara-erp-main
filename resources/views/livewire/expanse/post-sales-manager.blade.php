<div>
    @section('title', 'Post Sales')

    <div class="d-md-flex justify-content-between">
        <h2 class="mb-3"><span class="text-muted fw-light">Data @yield('title')</span></h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="javascript:void(0);">Expanse</a>
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
                    <div class="col-sm-8 col-xs-12 text-right">
                        <div class="d-md-flex justify-content-end">
                            <button type="button" wire:click="postMultiple()" class="btn btn-success" title="Receive"
                                data-bs-toggle="modal" data-bs-target="#PostModal"><i class="bx bx-send me-2"></i>Post
                                to Jurnal</button>
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
                            <th class="w-px-75"><input class="form-check-input" type="checkbox" id="checkAll"
                                    wire:model="selectAll">
                            </th>
                            <th class="sort" wire:click="sortOrder('invoice_number')">Invoice No
                                {!! $sortLink !!}
                            </th>
                            <th class="sort" wire:click="sortOrder('number')">Sales No
                                {!! $sortLink !!}
                            </th>
                            <th class="sort" wire:click="sortOrder('date')">Date {!! $sortLink !!}
                            </th>
                            <th class="sort" wire:click="sortOrder('customer_name')">Customer
                                {!! $sortLink !!}</th>
                            <th class="sort" wire:click="sortOrder('total')">Total {!! $sortLink !!}
                            </th>
                            <th class="sort" wire:click="sortOrder('approved_at')">Date Approved
                                {!! $sortLink !!}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($saless as $sales)
                            <tr>
                                <td class="text-center">
                                    @if ($sales->type == 'Tax')
                                        <input class="form-check-input" type="checkbox" value="{{ $sales->invoice_id }}"
                                            wire:model="selected">
                                    @else
                                        <input class="form-check-input" type="checkbox" value="{{ $sales->invoice_id }}"
                                            wire:model="selectedN">
                                    @endif
                                </td>
                                <td class="border-start text-center">{{ $sales->invoice_number }}</td>
                                <td class="border-start text-center">{{ $sales->number }}</td>
                                <td class="border-start text-center no-wrap">{{ $sales->invoice_date }}</td>
                                <td class="border-start">{{ $sales->customer_name }}</td>
                                <td class="border-start text-right">{{ number_format($sales->total, 2) }}</td>
                                <td class="border-start text-center">{{ $sales->approved_at }}</td>
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

    {{-- Post Sales --}}
    <div wire:ignore.self class="modal fade" id="PostModal" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered" post="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@yield('title') Multiple</h5>
                    <button type="button" class="btn-close" wire:click="closeModal" aria-label="Close"></button>
                </div>

                <form wire:submit.prevent="store">
                    <div class="modal-body">

                        <x-flash-alert />

                        <div class="accordion" id="accordionExample">
                            @foreach ($invPostMultiple as $key => $val)
                                <div class="card accordion-item active">
                                    <h2 class="accordion-header" id="heading{{ $key }}">
                                        <button type="button" class="accordion-button" data-bs-toggle="collapse"
                                            data-bs-target="#accordion{{ $key }}" aria-expanded="true"
                                            aria-controls="accordionOne{{ $key }}">
                                            {{ $val['number'] }}
                                        </button>
                                    </h2>

                                    <div id="accordion{{ $key }}" class="accordion-collapse collapse show"
                                        data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <div class="d-flex justify-content-between bg-lighter p-2">
                                                <p class="mb-0">Subtotal:</p>
                                                <p class="fw-medium mb-0">Rp.
                                                    {{ number_format($val['subtotal'], 2, ',', '.') }}</p>
                                            </div>
                                            @if ($val['type'] === 'Tax')
                                                <div class="d-flex justify-content-between p-2">
                                                    <p class="mb-0">PPN:</p>
                                                    <p class="fw-medium mb-0">Rp.
                                                        {{ number_format($val['ppn_amount'], 2, ',', '.') }}</p>
                                                </div>
                                            @endif
                                            <div
                                                class="d-flex justify-content-between @if ($val['type'] === 'Tax') bg-lighter @endif p-2">
                                                <p class="mb-0">Pengiriman:</p>
                                                <p class="fw-medium mb-0">Rp.
                                                    {{ number_format($val['delivery_fee'], 2, ',', '.') }}</p>
                                            </div>
                                            <div
                                                class="d-flex justify-content-between @if ($val['type'] === 'Non') bg-lighter @endif p-2">
                                                <p class="mb-0">Discount:</p>
                                                <p class="fw-medium mb-0">Rp.
                                                    {{ number_format($val['discount'], 2, ',', '.') }}</p>
                                            </div>
                                            <div
                                                class="d-flex justify-content-between @if ($val['type'] === 'Tax') bg-lighter @endif p-2">
                                                <p class="mb-0"><b>Total:</b></p>
                                                <p class="fw-medium mb-0"><b>Rp.
                                                        {{ number_format($val['total'], 2, ',', '.') }}</b></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary"
                            wire:click="closeModal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Post Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            window.addEventListener('checkall-indeterminate', event => {
                $("#checkAll").prop("indeterminate", true);
            });

            window.addEventListener('checkall-indeterminate-false', event => {
                $("#checkAll").prop("indeterminate", false);
            });

            window.addEventListener('checkall-checked', event => {
                $("#checkAll").prop("checked", true);
            });

            window.addEventListener('checkall-checked-false', event => {
                $("#checkAll").prop("checked", false);
            });

            window.addEventListener('close-modal', event => {
                $('#PostModal').modal('hide');
            });
        </script>
    @endpush
</div>
