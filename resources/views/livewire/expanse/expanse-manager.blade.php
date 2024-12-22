<div>
    @section('title', 'Expanse')

    <div class="d-md-flex justify-content-between">
        <h2 class="mb-3"><span class="text-muted fw-light">Data @yield('title')</span></h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="javascript:void(0);">@yield('title')</a>
                </li>
                <li class="breadcrumb-item active">@yield('title') Records</li>
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
                            <a href="{{ url('expanse/create') }}" class="btn btn-primary btn-sm"><i
                                    class="bx bx-plus me-2"></i>Add New</a>
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
                            <th class="sort" wire:click="sortOrder('tr_general_ledger.number')">Expanse Number
                                {!! $sortLink !!}
                            </th>
                            <th class="sort" wire:click="sortOrder('tr_general_ledger.date')">Date
                                {!! $sortLink !!}
                            </th>
                            <th class="sort" wire:click="sortOrder('tr_general_ledger.notes')">Summary
                                {!! $sortLink !!}</th>
                            <th class="sort" wire:click="sortOrder('tr_general_ledger.total_debit')">Total
                                {!! $sortLink !!}
                            </th>
                            <th class="w-px-150">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($generalLedgers as $gl)
                            <tr>
                                <td class="text-center">
                                    {{ ($generalLedgers->currentPage() - 1) * $generalLedgers->perPage() + $loop->index + 1 }}
                                </td>
                                <td class="border-start text-center">{{ $gl->number }}</td>
                                <td class="border-start text-center">{{ $gl->date }}</td>
                                <td class="border-start">{{ $gl->notes }}</td>
                                <td class="border-start text-right">{{ number_format($gl->total_debit, 2) }}</td>
                                <td class="border-start text-center">
                                    <button type="button" wire:click="view('{{ $gl->id }}')"
                                        class="btn btn-xs btn-success" title="Open Data"><span
                                            class="bx bx-folder-open"></span></button>
                                    <button type="button" wire:click="edit('{{ $gl->id }}')"
                                        class="btn btn-xs btn-secondary" title="Edit Data"><span
                                            class="bx bxs-edit"></span></button>
                                </td>
                            </tr>
                        @endforeach

                        @if ($generalLedgers->count() <= 0)
                            <tr>
                                <td colspan="6" class="text-center">No data..</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="panel-footer">
                {{ $generalLedgers->links('admin.custom-pagination-new') }}
            </div>
        </div>
    </div>

    @push('scripts')
        <script></script>
    @endpush
</div>
