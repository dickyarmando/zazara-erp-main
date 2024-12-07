<div>
    @section('title', 'Sales')

    <div class="d-md-flex justify-content-between">
        <h2 class="mb-3"><span class="text-muted fw-light">Data @yield('title')</span></h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="javascript:void(0);">@yield('title')</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ url('/sales') }}">SO Tax</a>
                </li>
                <li class="breadcrumb-item active">{{ $sales->number }}</li>
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
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <img src="{{ asset('picture/' . $companies->picture) }}" width="70%" />
                </div>
                <div class="col-md-6 text-right">
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
                    Sehubung dengan adanya proyek yang sedang bapak/ibu kerjakan, Berikut penawaran harga nya sebagai
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
                                $terbilang = $terbilang->format(round($sales->total, 2));
                                $terbilang = str_replace('juts', 'juta', $terbilang);
                            @endphp
                            <td colspan="4" rowspan="5"><b>Terbilang : <span
                                        class="text-capitalize">{{ $terbilang }}</span></b></td>
                            <td class="px-2 text-right" style="border: 1px solid #000;">SUBTOTAL</td>
                            <td class="px-2 text-right" style="border: 1px solid #000;">
                                {{ number_format($sales->subtotal, 2, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td class="px-2 text-right" style="border: 1px solid #000;">PPN ( {{ $sales->ppn }} % )
                            </td>
                            <td class="px-2 text-right" style="border: 1px solid #000;">
                                {{ number_format($sales->ppn_amount, 2, ',', '.') }}</td>
                        </tr>
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
                    Demikian Penawaran harga ini kami ajukan. Atas perhatian dan kerja sama nya kami ucapkan terima
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

    @push('scripts')
        <script>
            window.addEventListener('print', function() {
                var url = '{{ route('sales.view.print', ['id' => $sales->id]) }}';
                window.open(url, '_blank');
            });
        </script>
    @endpush
</div>
