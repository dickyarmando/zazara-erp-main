<div class="panel-body table-responsive position-relative">
    <div wire:loading class="position-absolute fs-1 top-50 start-50 z-3 text-info">
        <i class="fa fa-spin fa-spinner"></i>
    </div>
    <table class="table">
        <thead>
            <tr>
                <th class="w-px-75">No</th>
                <th class="sort no-wrap" wire:click="sortOrder('tr_purchase.number')">Purchase Number
                    {!! $sortLink !!}
                </th>
                <th class="sort no-wrap" wire:click="sortOrder('tr_purchase.reference')">Reference
                    {!! $sortLink !!}
                </th>
                <th class="sort no-wrap" wire:click="sortOrder('tr_purchase.date')">Date {!! $sortLink !!}
                </th>
                <th class="sort no-wrap" wire:click="sortOrder('ms_suppliers.company_name')">Supplier
                    {!! $sortLink !!}</th>
                <th class="sort no-wrap" wire:click="sortOrder('tr_purchase.notes')">Summary
                    {!! $sortLink !!}</th>
                <th class="sort no-wrap" wire:click="sortOrder('tr_purchase.total')">Total {!! $sortLink !!}
                </th>
                <th class="sort no-wrap" wire:click="sortOrder('tr_purchase.is_payed')">Payment
                    {!! $sortLink !!}
                </th>
                <th class="sort no-wrap" wire:click="sortOrder('tr_purchase.approved_at')">Status
                    {!! $sortLink !!}
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
                    <td class="border-start text-center">{{ $purchase->reference }}</td>
                    <td class="border-start text-center no-wrap">{{ $purchase->date }}</td>
                    <td class="border-start">{{ $purchase->supplier_name }}</td>
                    <td class="border-start unset">{{ $purchase->notes }}</td>
                    <td class="border-start text-right">{{ number_format($purchase->total, 0) }}</td>
                    <td class="border-start text-center">
                        @if ($purchase->is_payed == '1')
                            <span class="badge bg-label-success" text-capitalized> Paid </span>
                        @else
                            @if ($purchase->total_payment > 0)
                                <span class="badge bg-label-warning" text-capitalized> being paid </span>
                            @else
                                <span class="badge bg-label-danger" text-capitalized> Unpaid </span>
                            @endif
                        @endif
                    </td>
                    <td class="border-start text-center unset">
                        @if ($purchase->is_status == '0')
                            <span class="badge bg-label-danger" text-capitalized> Canceled </span>
                        @else
                            @if (isset($purchase->approved_at))
                                <span class="badge bg-label-success" text-capitalized> Approved </span>
                            @else
                                <span class="badge bg-label-warning" text-capitalized> Waiting Approve </span>
                            @endif
                        @endif
                    </td>
                    <td class="border-start text-center">
                        <button type="button" wire:click="view('{{ $purchase->id }}')" class="btn btn-xs btn-success"
                            title="Open Data"><span class="bx bx-folder-open"></span></button>
                        @if (!isset($purchase->approved_at))
                            @if ($userRoles->is_update == '1' && $purchase->is_status == '1')
                                <button type="button" wire:click="edit('{{ $purchase->id }}')"
                                    class="btn btn-xs btn-secondary" title="Edit User"><span
                                        class="bx bxs-edit"></span></button>
                            @endif
                        @endif
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
