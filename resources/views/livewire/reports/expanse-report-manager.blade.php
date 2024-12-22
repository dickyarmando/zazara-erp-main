<div>
    @section('title', 'Expanse Report')

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
                                    class="d-flex justify-content-between align-items-center card-widget-2 border-end pb-4 pb-sm-0">
                                    <div>
                                        <h4 class="mb-0">{{ number_format($generalLedgers->total()) }}</h4>
                                        <p class="mb-0">Expanse</p>
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
                                class="d-flex justify-content-between align-items-center card-widget-2 border-end pb-4 pb-sm-0">
                                <div>
                                    <h4 class="mb-0">Rp. {{ number_format($summaryGL->total_debit, 2) }}</h4>
                                    <p class="mb-0">Total Amount</p>
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
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Start Date</label>
                                <input type="date" wire:model="start_date"
                                    class="form-control @error('start_date') is-invalid @enderror"
                                    placeholder="Start Date">
                                @error('start_date')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">End Date</label>
                                <input type="date" wire:model="end_date"
                                    class="form-control @error('end_date') is-invalid @enderror" placeholder="End Date">
                                @error('end_date')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
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
                            </tr>
                        @endforeach

                        @if ($generalLedgers->count() <= 0)
                            <tr>
                                <td colspan="5" class="text-center">No data..</td>
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
