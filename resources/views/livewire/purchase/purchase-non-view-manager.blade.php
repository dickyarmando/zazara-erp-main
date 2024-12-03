<div>
    @section('title', 'Purchase')

    <div class="d-md-flex justify-content-between">
        <h2 class="mb-3"><span class="text-muted fw-light">Data @yield('title') Non Tax</span></h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="javascript:void(0);">@yield('title')</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ url('/purchase/non-tax') }}">PO Non Tax</a>
                </li>
                <li class="breadcrumb-item active">{{ $purchase->number }}</li>
            </ol>
        </nav>
    </div>

    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <button type="button" class="btn btn-label-secondary" wire:click="backRedirect"><span
                    class="bx bx-arrow-back me-2"></span> Back</button>
            <button type="button" wire:click="printDocument" class="btn btn-primary"><span
                    class="bx bx-printer me-2"></span> Print</button>
        </div>
        <div class="card-body" id="print-document">
            <div class="row">
                <div class="col-md-6">
                    <img src="{{ asset('picture/' . $companies->picture) }}" width="70%" />
                </div>
                <div class="col-md-6 text-right">
                    {!! $companies->address !!}
                </div>
                <div class="col-md-12">
                    <h2 class="text-center mt-5 text-black">PURCHASE ORDER</h2>
                </div>
                <div class="col-md-12">
                    <table cellpadding="1" cellspacing="1" width="100%">
                        <tr>
                            <td width="140px" class="px-2" style="border: 1px solid #000;">
                                Nomor PO
                            </td>
                            <td style="border: 1px solid #000;" class="px-2">{{ $purchase->number }}</td>
                        </tr>
                        <tr>
                            <td class="px-2" style="border: 1px solid #000;">
                                Tanggal PO
                            </td>
                            <td style="border: 1px solid #000;" class="px-2">
                                {{ date('d-m-Y', strtotime($purchase->date)) }}</td>
                        </tr>
                    </table>
                    <table cellpadding="1" cellspacing="1" width="100%" class="mt-4">
                        <tr>
                            <td width="140px" class="px-2" style="border: 1px solid #000;">
                                Kepada
                            </td>
                            <td style="border: 1px solid #000;" class="px-2">{{ $suppliers->company_name }}</td>
                        </tr>
                        <tr>
                            <td class="px-2" style="border: 1px solid #000;">
                                Up
                            </td>
                            <td style="border: 1px solid #000;" class="px-2">
                                {{ $suppliers->name }}</td>
                        </tr>
                    </table>
                    <table cellpadding="1" cellspacing="1" width="100%" class="mt-4">
                        <tr class="text-center">
                            <td width="20px" class="px-2" style="border: 1px solid #000;">
                                <b>No</b>
                            </td>
                            <td class="px-2" style="border: 1px solid #000;"><b>Nama Barang</b></td>
                            <td class="px-2" style="border: 1px solid #000;"><b>Satuan</b></td>
                            <td class="px-2" style="border: 1px solid #000;"><b>Qty</b></td>
                            <td class="px-2" style="border: 1px solid #000;"><b>Harga (Rp)</b></td>
                            <td class="px-2" style="border: 1px solid #000;"><b>Total (Rp)</b></td>
                        </tr>
                        @foreach ($items as $index => $item)
                            <tr>
                                <td width="20px" class="px-2 text-center" style="border: 1px solid #000;">
                                    {{ $index + 1 }}
                                </td>
                                <td class="px-2" style="border: 1px solid #000;">{{ $item['name'] }}</td>
                                <td class="px-2 text-center" style="border: 1px solid #000;">{{ $item['unit'] }}</td>
                                <td class="px-2 text-center" style="border: 1px solid #000;">{{ $item['qty'] }}</td>
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
                                $terbilang = $terbilang->format(round($purchase->total, 2));
                                $terbilang = str_replace('juts', 'juta', $terbilang);
                            @endphp
                            <td colspan="4" rowspan="4"><b>Terbilang : <span
                                        class="text-capitalize">{{ $terbilang }}</span></b></td>
                            <td class="px-2 text-right" style="border: 1px solid #000;">SUBTOTAL</td>
                            <td class="px-2 text-right" style="border: 1px solid #000;">
                                {{ number_format($purchase->subtotal, 2, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td class="px-2 text-right" style="border: 1px solid #000;">PENGIRIMAN
                            </td>
                            <td class="px-2 text-right" style="border: 1px solid #000;">
                                {{ number_format($purchase->delivery_fee, 2, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td class="px-2 text-right" style="border: 1px solid #000;">DISCOUNT
                            </td>
                            <td class="px-2 text-right" style="border: 1px solid #000;">
                                {{ number_format($purchase->discount, 2, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td class="px-2 text-right" style="border: 1px solid #000;"><b>TOTAL</b>
                            </td>
                            <td class="px-2 text-right" style="border: 1px solid #000;">
                                <b>{{ number_format($purchase->total, 2, ',', '.') }}</b>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            window.addEventListener('print', function() {
                const content = document.getElementById('print-document').innerHTML;
                const printWindow = window.open('', '', 'height=400,width=600');
                printWindow.document.write(content);
                printWindow.document.close();
                printWindow.print();
            });
        </script>
    @endpush
</div>
