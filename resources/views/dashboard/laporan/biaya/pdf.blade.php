<!DOCTYPE html>
<html lang="id">
<head>
    <title>LAPORAN TOP Transaksi Petugas</title>
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
            <td class="document-title">LAPORAN TOP TRANSAKSI PETUGAS</td>
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
            <td class="text-bold">Tanggal Laporan</td>
            <td>:</td>
            <td>{{ date('d F Y',strtotime(request()->segment(6))) }}</td>
        </tr>
    </table>
    <table class="table-transaksi">
        <thead style="background-color: darkred; color: white">
        <tr class="text-center">
            <th class="text-center" style="width: 8%">No</th>
            <th style="width: 40%">Petugas Tiket</th>
            <th style="width: 30%">Kota</th>
            <th style="width: 30%">Bus</th>
            <th style="width: 22%">Transaksi</th>
        </tr>
        </thead>
        <tbody>
        @php($i=1)
        @php($total = 0)
        @foreach($data as $d)
            <tr>
                <td class="text-center">{{ $i }}</td>
                <td>{{ $d->petugas }}</td>
                <td>{{ $d->kota }}</td>
                <td>{{ $d->bus }}</td>
                <td class="text-right">{{ number_format($d->total) }}</td>
            </tr>
            @php($total += $d->total)
            @php($i++)
        @endforeach
        <tr style="background-color: lightgray">
            <th class="text-center" colspan="4">TOTAL TRANSAKSI</th>
            <th class="text-right">{{ number_format($total) }}</th>
        </tr>
        </tbody>

    </table>
</main>


</body>
</html>