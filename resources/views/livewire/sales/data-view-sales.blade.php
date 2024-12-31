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
        Sehubung dengan adanya proyek yang sedang bapak/ibu kerjakan, Berikut penawaran harga nya sebagai
        berikut :

        <table cellpadding="1" cellspacing="1" width="100%" class="mt-4">
            <tr class="text-center">
                <td width="20px" class="px-2" style="border: 1px solid #000;">
                    <b>No</b>
                </td>
                <td class="px-2" style="border: 1px solid #000;"><b>Kode Barang</b></td>
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
                    <td class="px-2" style="border: 1px solid #000;">{{ $item['code'] }}</td>
                    <td class="px-2" style="border: 1px solid #000;">{{ $item['name'] }}</td>
                    <td class="px-2 text-center" style="border: 1px solid #000;">{{ $item['qty'] }}</td>
                    <td class="px-2 text-center" style="border: 1px solid #000;">{{ $item['unit'] }}</td>
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
                <td colspan="5" rowspan="5"><b>Terbilang : <span
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
    <div class="col-md-12">
        <b>Notes :</b>
        <p>{{ $sales->notes }}</p>
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
        <div style="height: 100px;">&nbsp;</div></br>
        <b>{{ $soSign->value }}</b>
    </div>
</div>
