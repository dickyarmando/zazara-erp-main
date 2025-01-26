<div class="row">
    <div class="col-6">
        <img src="{{ asset('picture/' . $companies->picture) }}" width="70%" />
    </div>
    <div class="col-6 text-right">
        {!! $companies->address !!}
    </div>
    <div class="col-md-12">
        <h2 class="text-center mt-5 text-black">General Ledger</h2>
    </div>
    <div class="col-md-12">
        <table cellpadding="1" cellspacing="1" width="100%">
            <tr>
                <td width="140px" class="px-2" style="border: 1px solid #000;">
                    Nomor
                </td>
                <td style="border: 1px solid #000;" class="px-2">{{ $trGl->number }}</td>
            </tr>
            <tr>
                <td class="px-2" style="border: 1px solid #000;">
                    Tanggal
                </td>
                <td style="border: 1px solid #000;" class="px-2">
                    {{ date('d-m-Y', strtotime($trGl->date)) }}</td>
            </tr>
        </table>
        <table cellpadding="1" cellspacing="1" width="100%" class="mt-4">
            <tr class="text-center">
                <td width="20px" class="px-2" style="border: 1px solid #000;">
                    <b>No</b>
                </td>
                <td class="px-2" style="border: 1px solid #000;"><b>Nama Account</b></td>
                <td class="px-2" style="border: 1px solid #000;"><b>Debit (Rp)</b></td>
                <td class="px-2" style="border: 1px solid #000;"><b>Credit (Rp)</b></td>
            </tr>
            @foreach ($trGlDetails as $index => $item)
                <tr>
                    <td width="20px" class="px-2 text-center" style="border: 1px solid #000;">
                        {{ $index + 1 }}
                    </td>
                    <td class="px-2" style="border: 1px solid #000;">{{ $item['account_name'] }}</td>
                    <td class="px-2 text-right" style="border: 1px solid #000;">
                        @if ($item['type'] == 'db')
                            {{ number_format($item['amount'], 2, ',', '.') }}
                        @else
                            {{ number_format(0, 2, ',', '.') }}
                        @endif
                    </td>
                    <td class="px-2 text-right" style="border: 1px solid #000;">
                        @if ($item['type'] == 'cr')
                            {{ number_format($item['amount'], 2, ',', '.') }}
                        @else
                            {{ number_format(0, 2, ',', '.') }}
                        @endif
                    </td>
                </tr>
            @endforeach
            <tr>
                <td class="px-2 text-right" colspan="2" style="border: 1px solid #000;">TOTAL</td>
                <td class="px-2 text-right" style="border: 1px solid #000;">
                    {{ number_format($trGl->total_debit, 2, ',', '.') }}</td>
                <td class="px-2 text-right" style="border: 1px solid #000;">
                    {{ number_format($trGl->total_credit, 2, ',', '.') }}</td>
            </tr>
        </table>
    </div>
    <div class="col-md-12 mt-4">
        <b>Notes :</b></br>
        <span>{{ $trGl->notes }}</span>
    </div>
</div>
