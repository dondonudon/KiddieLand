<!DOCTYPE html>
<html lang="id">
<head>
    <title>LAPORAN Transaksi Detail per Petugas</title>
    <link rel="stylesheet" href="{{ public_path('assets/pdf/portrait.css') }}">
</head>
<body>
<header>
    <table style="width: 100%">
        <tr>
            <td rowspan="3" style="width: 20%">
                <img src="assets/logo/with-name/logo-1000.png" class="logo" alt="Logo">
            </td>
            <td class="header-brt" style="width: 60%">BUS RAPID TRANSIT</td>
            <td rowspan="3"></td>
        </tr>
        <tr>
            <td class="header-tj">TRANS JAWA TENGAH</td>
        </tr>
        <tr>
            <td class="document-title">LAPORAN TRANSAKSI PETUGAS DETAIL</td>
        </tr>
    </table>
    <hr>
</header>

<footer>
    <table style="width: 100%">
        <tr>
            <td>Dicetak oleh {{ request()->session()->get('name') }} ({{ date('d F Y - H:i:s') }})</td>
        </tr>
    </table>
</footer>

<main>
    <table>
        <tr>
            <td class="text-bold">Petugas</td>
            <td>:</td>
            <td>{{ $user->name }}</td>
        </tr>
        <tr>
            <td class="text-bold">Tanggal Laporan</td>
            <td>:</td>
            <td>{{ date('d F Y',strtotime(request()->segment(6))) }}</td>
        </tr>
    </table>
    <table class="table-transaksi">
        <thead style="background-color: darkred; color: white">
        <tr class="text-center">
            <th class="text-center" style="width: 5%">No</th>
            <th style="width: 25%">No Transaksi</th>
            <th style="width: 10%">Jam</th>
            <th style="width: 10%">Penumpang</th>
            <th style="width: 15%">Pembayaran</th>
            <th style="width: 25%">Bus</th>
            <th style="width: 15%">Total</th>
        </tr>
        </thead>
        <tbody>
        @php($i=1)
        @php($total = 0)
        @foreach($data as $d)
            <tr style="{{ $d->penumpang == 'Transit' ? 'background-color: lightgreen;' : '' }}">
                <td class="text-center">{{ $i }}</td>
                <td>{{ $d->no_transaksi }}</td>
                <td class="text-right">{{ $d->jam_transaksi }}</td>
                <td>{{ $d->penumpang }}</td>
                <td>{{ $d->pembayaran }}</td>
                <td>{{ $d->bus }}</td>
                <td class="text-right">{{ number_format($d->harga) }}</td>
            </tr>
            @php($total += $d->harga)
            @php($i++)
        @endforeach
        <tr style="background-color: lightgray">
            <th class="text-center" colspan="6">TOTAL PENDAPATAN</th>
            <th class="text-right">{{ number_format($total) }}</th>
        </tr>
        </tbody>
    </table>
</main>


</body>
</html>