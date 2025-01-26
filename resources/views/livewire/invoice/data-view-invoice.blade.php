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
    <div class="col-md-12 text-center">
        <h2 class="text-uppercase text-black" style="text-decoration: underline"><b>Invoice</b></h2>
    </div>
    <div class="col-6 mt-4">
        <div class="row">
            <div class="col-5">
                No. Invoice
            </div>
            <div class="col-7">
                : {{ $invoices->number }}
            </div>
            <div class="col-5">
                Tanggal Invoice
            </div>
            <div class="col-7">
                : {{ date('d-m-Y', strtotime($invoices->date)) }}
            </div>
            <div class="col-5">
                No. SO
            </div>
            <div class="col-7">
                : {{ $sales->number }}
            </div>
            <div class="col-5">
                Due Date
            </div>
            <div class="col-7">
                : @if ($invoices->due_termin == 0)
                    CBD
                @else
                    {{ date('d-m-Y', strtotime($invoices->due_date)) }}
                @endif
            </div>
        </div>
    </div>
    <div class="col-6 mt-4">
        <div class="row">
            <div class="col-3">
                Kepada
            </div>
            <div class="col-9">
                : {{ $customers->company_name }}
            </div>
            <div class="col-3">
                NPWP
            </div>
            <div class="col-9">
                : {{ $customers->npwp }}
            </div>
            <div class="col-3">
                Alamat
            </div>
            <div class="col-6">
                : {{ $customers->address }}
            </div>
        </div>
    </div>
    <div class="col-md-12 mt-2">
        <table cellpadding="1" cellspacing="1" width="100%" class="mt-4">
            <tr class="text-center">
                <td width="20px" class="px-2" style="border: 1px solid #000;">
                    <b>No</b>
                </td>
                <td class="px-2" style="border: 1px solid #000;"><b>Nama Barang</b></td>
                <td class="px-2" style="border: 1px solid #000;"><b>Qty</b></td>
                <td class="px-2" style="border: 1px solid #000;"><b>UoM</b></td>
                <td class="px-2" style="border: 1px solid #000;min-width: 120px;"><b>Harga (Rp)</b></td>
                <td class="px-2" style="border: 1px solid #000;min-width: 120px;"><b>Total (Rp)</b></td>
            </tr>
            @foreach ($items as $index => $item)
                <tr>
                    <td width="20px" class="px-2 text-center" style="border: 1px solid #000;">
                        {{ $index + 1 }}
                    </td>
                    <td class="px-2" style="border: 1px solid #000;">{{ $item['name'] }}</td>
                    <td class="px-2 text-right" style="border: 1px solid #000;">
                        {{ number_format($item['qty'], 0, ',', '.') }}</td>
                    <td class="px-2 text-center" style="border: 1px solid #000;">{{ $item['unit'] }}</td>
                    <td class="px-2 text-right" style="border: 1px solid #000;">
                        {{ number_format($item['price'], 0, ',', '.') }}
                    </td>
                    <td class="px-2 text-right" style="border: 1px solid #000;">
                        {{ number_format($item['total'], 0, ',', '.') }}
                    </td>
                </tr>
            @endforeach
            <tr>
                @php
                    $terbilang = new \NumberFormatter('id', \NumberFormatter::SPELLOUT);
                    $terbilang = $terbilang->format(round($sales->total, 0));
                    $terbilang = str_replace('juts', 'juta', $terbilang);
                @endphp
                <td colspan="4" rowspan="5"><b>Terbilang : <span
                            class="text-capitalize">{{ $terbilang }}</span></b></td>
                <td class="px-2 text-right" style="border: 1px solid #000;">SUBTOTAL</td>
                <td class="px-2 text-right" style="border: 1px solid #000;">
                    {{ number_format($sales->subtotal, 0, ',', '.') }}</td>
            </tr>
            @if (isset($sales->ppn))
                <tr>
                    <td class="px-2 text-right" style="border: 1px solid #000;">PPN
                    </td>
                    <td class="px-2 text-right" style="border: 1px solid #000;">
                        {{ number_format($sales->ppn_amount, 0, ',', '.') }}</td>
                </tr>
            @endif
            <tr>
                <td class="px-2 text-right" style="border: 1px solid #000;">PENGIRIMAN
                </td>
                <td class="px-2 text-right" style="border: 1px solid #000;">
                    {{ number_format($sales->delivery_fee, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="px-2 text-right" style="border: 1px solid #000;">DISCOUNT
                </td>
                <td class="px-2 text-right" style="border: 1px solid #000;">
                    {{ number_format($sales->discount, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="px-2 text-right" style="border: 1px solid #000;"><b>TOTAL</b>
                </td>
                <td class="px-2 text-right" style="border: 1px solid #000;">
                    <b>{{ number_format($sales->total, 0, ',', '.') }}</b>
                </td>
            </tr>
        </table>
    </div>
    <div class="col-md-12">
        <b>Notes :</b>
        <p>{{ $invoices->notes }}</p>
    </div>
    <div class="col-4 mt-4 text-center">
        Diterima Oleh</br>
        <div style="height: 100px;">&nbsp;</div></br>
        <b>(<span style="padding-left:150px;">&nbsp;</span>)</b>
    </div>
    <div class="col-4 mt-4 text-center">
        &nbsp;
    </div>
    <div class="col-4 mt-4 text-center">
        Diketahui Oleh</br>
        <div style="height: 100px;">
            @if (!empty($invSignImg->value))
                <img src="{{ asset('assets/img/config/' . $invSignImg->value) }}" class="img-fluid mt-2"
                    style="height: 100px;">
            @endif
        </div></br>
        <b>Nama : {{ $invSignName->value }}</b><br>
        <b>Jabatan : {{ $invSignPosition->value }}</b>
    </div>
    <div class="col-md-8 mt-4">
        {!! $invTC->value !!}
    </div>
</div>
