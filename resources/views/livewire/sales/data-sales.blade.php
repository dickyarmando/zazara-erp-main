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
                <th class="sort" wire:click="sortOrder('tr_sales.is_payed')">Status {!! $sortLink !!}
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
                    <td class="border-start text-center">{{ $sales->date }}</td>
                    <td class="border-start">{{ $sales->customer_name }}</td>
                    <td class="border-start" style="white-space: unset;">{{ $sales->notes }}</td>
                    <td class="border-start text-right">{{ number_format($sales->total, 2) }}</td>
                    <td class="border-start text-center">
                        @if ($sales->is_payed == '1')
                            <span class="badge bg-label-success" text-capitalized> Paid </span>
                        @else
                            @if ($sales->total_payment > 0)
                                <span class="badge bg-label-warning" text-capitalized> being paid </span>
                            @else
                                <span class="badge bg-label-danger" text-capitalized> Unpaid </span>
                            @endif
                        @endif
                    </td>
                    <td class="border-start text-center">
                        <button type="button" wire:click="view('{{ $sales->id }}')" class="btn btn-xs btn-success"
                            title="Open Data"><span class="bx bx-folder-open"></span></button>
                        @if ($sales->total_payment == 0)
                            <button type="button" wire:click="edit('{{ $sales->id }}')"
                                class="btn btn-xs btn-secondary" title="Edit User"><span
                                    class="bx bxs-edit"></span></button>
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
