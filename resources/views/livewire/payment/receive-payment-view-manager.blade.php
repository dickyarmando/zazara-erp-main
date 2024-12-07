<div>
    @section('title', 'Receive Payments')

    <div class="d-md-flex justify-content-between">
        <h2 class="mb-3"><span class="text-muted fw-light">Data @yield('title')</span></h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="javascript:void(0);">Payments</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ url('/receive') }}">@yield('title')</a>
                </li>
                <li class="breadcrumb-item active">{{ $sales->number }}</li>
            </ol>
        </nav>
    </div>

    <x-flash-alert />

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <img src="{{ asset('picture/' . $companies->picture) }}" width="70%" />
                        </div>
                        <div class="col-6 text-right">
                            {!! $companies->address !!}
                        </div>
                        <div class="col-md-12 mt-2" style="border-top:4px solid #000;">
                            &nbsp;
                        </div>
                        <div class="col-md-12 text-right">
                            Jakarta, {{ date('d-m-Y', strtotime($sales->date)) }}
                        </div>
                        <div class="col-md-12">
                            NO : {{ $sales->number }}
                        </div>
                        <div class="col-md-12 mt-4">
                            Kepada Yth,</br>
                            {{ $customers->company_name }}</br>
                            UP : {{ $customers->name }}
                        </div>
                        <div class="col-md-12 mt-4">
                            Dengan Hormat,</br>
                            Sehubung dengan adanya proyek yang sedang bapak/ibu kerjakan, Berikut penawaran harga nya
                            sebagai
                            berikut :

                            <table cellpadding="1" cellspacing="1" width="100%" class="mt-4">
                                <tr class="text-center">
                                    <td width="20px" class="px-2" style="border: 1px solid #000;">
                                        <b>No</b>
                                    </td>
                                    <td class="px-2" style="border: 1px solid #000;"><b>Nama Barang</b></td>
                                    <td class="px-2" style="border: 1px solid #000;"><b>Qty</b></td>
                                    <td class="px-2" style="border: 1px solid #000;"><b>Satuan</b></td>
                                    <td class="px-2" style="border: 1px solid #000;"><b>Harga (Rp)</b></td>
                                    <td class="px-2" style="border: 1px solid #000;"><b>Total (Rp)</b></td>
                                </tr>
                                @foreach ($items as $index => $item)
                                    <tr>
                                        <td width="20px" class="px-2 text-center" style="border: 1px solid #000;">
                                            {{ $index + 1 }}
                                        </td>
                                        <td class="px-2" style="border: 1px solid #000;">{{ $item['name'] }}</td>
                                        <td class="px-2 text-center" style="border: 1px solid #000;">{{ $item['unit'] }}
                                        </td>
                                        <td class="px-2 text-center" style="border: 1px solid #000;">
                                            {{ $item['qty'] }}</td>
                                        <td class="px-2 text-right" style="border: 1px solid #000;">
                                            {{ number_format($item['price'], 2, ',', '.') }}
                                        </td>
                                        <td class="px-2 text-right" style="border: 1px solid #000;">
                                            {{ number_format($item['total'], 2, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                                <tr>
                                    @php
                                        $terbilang = new \NumberFormatter('id', \NumberFormatter::SPELLOUT);
                                        $terbilang = $terbilang->format(round($sales->total, 2));
                                        $terbilang = str_replace('juts', 'juta', $terbilang);
                                    @endphp
                                    <td colspan="4" rowspan="5"><b>Terbilang : <span
                                                class="text-capitalize">{{ $terbilang }}</span></b></td>
                                    <td class="px-2 text-right" style="border: 1px solid #000;">SUBTOTAL</td>
                                    <td class="px-2 text-right" style="border: 1px solid #000;">
                                        {{ number_format($sales->subtotal, 2, ',', '.') }}</td>
                                </tr>
                                @if ($set_type == 'Tax')
                                    <tr>
                                        <td class="px-2 text-right" style="border: 1px solid #000;">PPN (
                                            {{ $sales->ppn }} % )
                                        </td>
                                        <td class="px-2 text-right" style="border: 1px solid #000;">
                                            {{ number_format($sales->ppn_amount, 2, ',', '.') }}</td>
                                    </tr>
                                @endif
                                <tr>
                                    <td class="px-2 text-right" style="border: 1px solid #000;">PENGIRIMAN
                                    </td>
                                    <td class="px-2 text-right" style="border: 1px solid #000;">
                                        {{ number_format($sales->delivery_fee, 2, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td class="px-2 text-right" style="border: 1px solid #000;">DISCOUNT
                                    </td>
                                    <td class="px-2 text-right" style="border: 1px solid #000;">
                                        {{ number_format($sales->discount, 2, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td class="px-2 text-right" style="border: 1px solid #000;"><b>TOTAL</b>
                                    </td>
                                    <td class="px-2 text-right" style="border: 1px solid #000;">
                                        <b>{{ number_format($sales->total, 2, ',', '.') }}</b>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-12 mt-4">
                            <u><b>Terms & Conditions</b></u>
                            {!! $soTC->value !!}
                        </div>
                        <div class="col-md-12 mt-2">
                            Demikian Penawaran harga ini kami ajukan. Atas perhatian dan kerja sama nya kami ucapkan
                            terima
                            kasih.
                        </div>
                        <div class="col-md-12 mt-4">
                            Hormat Kami,</br>
                            <div style="height: 60px;">&nbsp;</div></br>
                            <b>{{ $soSign->value }}</b>
                        </div>
                    </div>
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
                        @if ($sales->is_receive == 0)
                            <div class="col-md-12 p-2">
                                <button class="btn btn-success w-100" data-bs-toggle="modal"
                                    data-bs-target="#ReceiveModal"><span class="bx bx-dollar me-2"></span>
                                    Receive</button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-offset-1 col-md-12 mt-4">
                <div class="panel">
                    <div class="panel-heading">
                        <h5 class="panel-title">Received History</h5>
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
                                    $balance = $sales->total;
                                @endphp
                                @foreach ($receives as $key => $receive)
                                    @php
                                        $balance = $balance - $receive->amount;
                                    @endphp
                                    <tr>
                                        <td class="text-center">
                                            {{ $key + 1 }}
                                        </td>
                                        <td class="border-start text-center">{{ $receive->date }}</td>
                                        <td class="border-start text-right">
                                            {{ number_format($receive->amount, 2) }}</td>
                                        <td class="border-start text-right">
                                            {{ number_format($balance, 2) }}</td>
                                    </tr>
                                @endforeach

                                @if ($receives->count() <= 0)
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
    <div wire:ignore.self class="modal fade" id="ReceiveModal" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" receive="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@yield('title')</h5>
                    <button type="button" class="btn-close" wire:click="closeModal" aria-label="Close"></button>
                </div>

                <form wire:submit.prevent="store">
                    <div class="modal-body">

                        <x-flash-alert />

                        <div class="d-flex justify-content-between bg-lighter p-2 mb-4">
                            <p class="mb-0">Sales Balance:</p>
                            <p class="fw-medium mb-0">Rp. {{ number_format($sales->rest, 2, ',', '.') }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Receive Amount <span class="text-danger">*</span></label>
                            <input type="text" wire:model="amount"
                                class="form-control @error('amount') is-invalid @enderror"
                                placeholder="Rp. {{ number_format($sales->rest, 2, ',', '.') }}">
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
                        <button type="button" class="btn btn-label-secondary"
                            wire:click="closeModal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            window.addEventListener('print-tax', function() {
                var url = '{{ route('sales.view.print', ['id' => $sales->id]) }}';
                window.open(url, '_blank');
            });

            window.addEventListener('print-non', function() {
                var url = '{{ route('sales.non.view.print', ['id' => $sales->id]) }}';
                window.open(url, '_blank');
            });

            window.addEventListener('close-modal', event => {
                $('#ReceiveModal').modal('hide');
            });
        </script>
    @endpush
</div>
