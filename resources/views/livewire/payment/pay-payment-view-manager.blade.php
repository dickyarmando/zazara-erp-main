<div>
    @section('title', 'Pay Payments')

    <div class="d-md-flex justify-content-between">
        <h2 class="mb-3"><span class="text-muted fw-light">Data @yield('title')</span></h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="javascript:void(0);">Payments</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ url('/pay') }}">@yield('title')</a>
                </li>
                <li class="breadcrumb-item active">{{ $purchase->number }}</li>
            </ol>
        </nav>
    </div>

    <x-flash-alert />

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    @if ($set_type == 'Tax')
                        @include('livewire.purchase.data-view-purchase')
                    @elseif($set_type == 'Non')
                        @include('livewire.purchase.data-view-purchase-non')
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 p-2">
                            <button type="button" class="btn btn-label-secondary w-100" wire:click="backRedirect"><span
                                    class="bx bx-arrow-back me-2"></span> Back</button>
                        </div>
                        <div class="col-md-6 p-2">
                            <button class="btn btn-primary w-100" wire:click="print"><span
                                    class="bx bx-printer me-2"></span>
                                Print</button>
                        </div>
                        @if ($purchase->is_payed == 0)
                            <div class="col-md-12 p-2">
                                <button class="btn btn-success w-100" data-bs-toggle="modal"
                                    data-bs-target="#PaymentModal"><span class="bx bx-dollar me-2"></span>
                                    Payment</button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-offset-1 col-md-12 mt-4">
                <div class="panel">
                    <div class="panel-heading">
                        <h5 class="panel-title">Payment History</h5>
                    </div>
                    <div class="panel-body table-responsive position-relative">
                        <div wire:loading class="position-absolute fs-1 top-50 start-50 z-3 text-info">
                            <i class="fa fa-spin fa-spinner"></i>
                        </div>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th class="w-px-25">No</th>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Balance</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $balance = $purchase->total;
                                @endphp
                                @foreach ($payments as $key => $payment)
                                    @php
                                        $balance = $balance - $payment->amount;
                                    @endphp
                                    <tr>
                                        <td class="text-center">
                                            {{ $key + 1 }}
                                        </td>
                                        <td class="border-start text-center">{{ $payment->date }}</td>
                                        <td class="border-start text-right">
                                            {{ number_format($payment->amount, 2) }}</td>
                                        <td class="border-start text-right">
                                            {{ number_format($balance, 2) }}</td>
                                    </tr>
                                @endforeach

                                @if ($payments->count() <= 0)
                                    <tr>
                                        <td colspan="4" class="text-center">No data..</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Edit --}}
    <div wire:ignore.self class="modal fade" id="PaymentModal" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" payment="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@yield('title')</h5>
                    <button type="button" class="btn-close" wire:click="closeModal" aria-label="Close"></button>
                </div>

                <form wire:submit.prevent="store">
                    <div class="modal-body">

                        <x-flash-alert />

                        <div class="d-flex justify-content-between bg-lighter p-2 mb-4">
                            <p class="mb-0">Purchase Balance:</p>
                            <p class="fw-medium mb-0">Rp. {{ number_format($purchase->rest, 2, ',', '.') }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Payment Amount <span class="text-danger">*</span></label>
                            <input type="text" wire:model="amount"
                                class="form-control @error('amount') is-invalid @enderror"
                                placeholder="Rp. {{ number_format($purchase->rest, 2, ',', '.') }}">
                            @error('amount')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Date <span class="text-danger">*</span></label>
                            <input type="date" wire:model="date"
                                class="form-control @error('date') is-invalid @enderror" placeholder="Date">
                            @error('date')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            @inject('paymentMethods', 'App\Models\MsPaymentMethods')
                            <label class="form-label">Payment Method <span class="text-danger">*</span></label>
                            <select wire:model="payment_method_id"
                                class="form-select @error('payment_method_id') is-invalid @enderror">
                                @foreach ($paymentMethods::where('is_status', '=', '1')->orderBy('name')->select('id', 'name')->get() as $key => $val)
                                    <option value="{{ $val['id'] }}">
                                        {{ $val['name'] }}</option>
                                @endforeach
                            </select>
                            @error('payment_method_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Notes</label>
                            <textarea wire:model="notes" class="form-control @error('notes') is-invalid @enderror" placeholder="Notes"
                                rows="3"></textarea>
                            @error('notes')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" wire:click="closeModal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            window.addEventListener('print-tax', function() {
                var url = '{{ route('purchase.view.print', ['id' => $purchase->id]) }}';
                window.open(url, '_blank');
            });

            window.addEventListener('print-non', function() {
                var url = '{{ route('purchase.non.view.print', ['id' => $purchase->id]) }}';
                window.open(url, '_blank');
            });

            window.addEventListener('close-modal', event => {
                $('#PaymentModal').modal('hide');
            });
        </script>
    @endpush
</div>
