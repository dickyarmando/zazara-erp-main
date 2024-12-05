<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Purchase - {{ $purchase->number }}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <!-- Core head -->
    @include('admin.partials.head', ['vendor' => isset($vendor) ? $vendor : []])
</head>

<body>
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
                    <td colspan="4" rowspan="5"><b>Terbilang : <span
                                class="text-capitalize">{{ $terbilang }}</span></b></td>
                    <td class="px-2 text-right" style="border: 1px solid #000;">SUBTOTAL</td>
                    <td class="px-2 text-right" style="border: 1px solid #000;">
                        {{ number_format($purchase->subtotal, 2, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="px-2 text-right" style="border: 1px solid #000;">PPN ( {{ $purchase->ppn }} % )
                    </td>
                    <td class="px-2 text-right" style="border: 1px solid #000;">
                        {{ number_format($purchase->ppn_amount, 2, ',', '.') }}</td>
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

    <script type="text/javascript">
        window.print();
        setTimeout("window.close();", 1000);
    </script>
</body>

</html>
