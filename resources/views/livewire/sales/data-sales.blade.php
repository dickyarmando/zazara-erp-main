<div class="panel-body table-responsive position-relative">
    <div wire:loading class="position-absolute fs-1 top-50 start-50 z-3 text-info">
        <i class="fa fa-spin fa-spinner"></i>
    </div>
    <table class="table">
        <thead>
            <tr>
                <th class="w-px-75">No</th>
                <th class="sort" wire:click="sortOrder('tr_sales.number')">Sales Number
                    {!! $sortLink !!}
                </th>
                <th class="sort" wire:click="sortOrder('tr_sales.date')">Date {!! $sortLink !!}
                </th>
                <th class="sort" wire:click="sortOrder('ms_customers.company_name')">Customer
                    {!! $sortLink !!}</th>
                <th class="sort" wire:click="sortOrder('tr_sales.notes')">Summary
                    {!! $sortLink !!}</th>
                <th class="sort" wire:click="sortOrder('tr_sales.total')">Total {!! $sortLink !!}
                </th>
                {{-- <th class="sort" wire:click="sortOrder('tr_sales.is_payed')">Payment {!! $sortLink !!}
                </th> --}}
                <th class="sort" wire:click="sortOrder('tr_sales.approved_at')">Status {!! $sortLink !!}
                </th>
                <th class="w-px-150">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($saless as $sales)
                <tr>
                    <td class="text-center">
                        {{ ($saless->currentPage() - 1) * $saless->perPage() + $loop->index + 1 }}
                    </td>
                    <td class="border-start text-center">{{ $sales->number }}</td>
                    <td class="border-start text-center no-wrap">{{ $sales->date }}</td>
                    <td class="border-start">{{ $sales->customer_name }}</td>
                    <td class="border-start unset">{{ $sales->notes }}</td>
                    <td class="border-start text-right">{{ number_format($sales->total, 0) }}</td>
                    {{-- <td class="border-start text-center">
                        @if ($sales->is_payed == '1')
                            <span class="badge bg-label-success" text-capitalized> Paid </span>
                        @else
                            @if ($sales->total_payment > 0)
                                <span class="badge bg-label-warning" text-capitalized> being paid </span>
                            @else
                                <span class="badge bg-label-danger" text-capitalized> Unpaid </span>
                            @endif
                        @endif
                    </td> --}}
                    <td class="border-start text-center unset">
                        @if ($sales->is_status == '1')
                            @if (isset($sales->approved_at))
                                <span class="badge bg-label-success" text-capitalized> Approved </span>
                            @else
                                <span class="badge bg-label-warning" text-capitalized> Waiting Approve </span>
                            @endif
                        @else
                            <span class="badge bg-label-danger" text-capitalized> Cancelled </span>
                        @endif
                    </td>
                    <td class="border-start text-center">
                        @if ($sales->is_invoice == '1')
                            <div class="btn-group" role="group">
                                <button id="btnData" type="button" class="btn btn-xs btn-success dropdown-toggle"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span
                                        class="bx bx-folder-open"></span></button>
                                <div class="dropdown-menu" aria-labelledby="btnData">
                                    <button class="dropdown-item" wire:click="view('{{ $sales->id }}')">Sales
                                        Order</button>
                                    <button class="dropdown-item" wire:click="viewInvoice('{{ $sales->invoice_id }}')"
                                        href="javascript:void(0);">Invoice</button>
                                </div>
                            </div>
                        @else
                            <button type="button" wire:click="view('{{ $sales->id }}')"
                                class="btn btn-xs btn-success" title="Open Data"><span
                                    class="bx bx-folder-open"></span></button>
                        @endif
                        @if ($userRoles->is_update == '1' && $sales->is_status == '1')
                            <button type="button" wire:click="edit('{{ $sales->id }}')"
                                class="btn btn-xs btn-secondary" title="Edit Data"><span
                                    class="bx bxs-edit"></span></button>
                        @endif
                        @if ($sales->is_invoice == '0' && $sales->approved_at != null)
                            @if (isset($userRolesReceives))
                                @if ($userRolesReceives->is_show == '1')
                                    <button type="button" wire:click="createInvoice('{{ $sales->id }}')"
                                        class="btn btn-xs btn-primary" title="Create Invoice"><span
                                            class="bx bxs-receipt"></span></button>
                                @endif
                            @endif
                        @endif
                    </td>
                </tr>
            @endforeach

            @if ($saless->count() <= 0)
                <tr>
                    <td colspan="8" class="text-center">No data..</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>
<div class="panel-footer">
    {{ $saless->links('admin.custom-pagination-new') }}
</div>
