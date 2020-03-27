<!DOCTYPE html>
<html lang="id">
<head>
    <title>LAPORAN Transaksi Petugas</title>
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
            <td class="document-title">LMB - Shift Pagi </td>
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
    <table class="table-transaksi" style="width: 100%; padding-top: 0.2cm">
        <thead style="background-color: darkred; color: white">
        <tr class="text-center">
            <th class="text-center" style="width: 8%">No</th>
            <th style="width: 40%">Shelter</th>
            <th style="width: 30%">Umum</th>
            <th style="width: 30%">Buruh</th>
            <th style="width: 30%">Veteran</th>
            <th style="width: 30%">Pelajar</th>
            <th style="width: 30%">Turun</th>
        </tr>
        </thead>
        <tbody>
        @php($i=1)
        @php($total = 0)
        @foreach($data as $d)
            <tr>
                <td class="text-center">{{ $i }}</td>
                <td>{{ $d->nama }}</td>
                <td>{{ $d->Upagi }}</td>
                <td>{{ $d->Bpagi }}</td>
                <td>{{ $d->Vpagi }}</td>
                <td>{{ $d->Ppagi }}</td>
                <td>{{ $d->Tpagi }}</td>
            </tr>
            @php($i++)
        @endforeach
        <!-- <tr style="background-color: lightgray">
            <th class="text-center">TOTAL </th>
            <th class="text-right">{{ number_format($total) }}</th>
        </tr> -->
        </tbody>

    </table>
</main>


</body>
</html>