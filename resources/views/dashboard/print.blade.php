<!DOCTYPE html>
<html lang="id">
<head>
    <title>print pdf</title>
    <style>
        @page {
            font-family: "Lucida Grande", Arial, Helvetica, sans-serif;
            font-size: 15px;
            margin: 5px;
        }
    </style>
</head>
<body>
<table style="width: 100%">
    <tr style="vertical-align: top">
        <td colspan="3" style="text-align: center">WAVE Solusi Indonesia</td>
    </tr>
</table>

{{--<table style="width: 100%; padding-top: 5px">--}}
{{--    <thead style="border-bottom: 1px solid black">--}}
{{--    <tr style="vertical-align: top">--}}
{{--        <th>Produk</th>--}}
{{--        <th style="text-align: right">Qty</th>--}}
{{--        <th style="text-align: right">Total</th>--}}
{{--    </tr>--}}
{{--    </thead>--}}
{{--    <tbody>--}}
{{--    <tr style="vertical-align: top">--}}
{{--        <td>Pencil</td>--}}
{{--        <td style="text-align: right">10</td>--}}
{{--        <td style="text-align: right">10.000</td>--}}
{{--    </tr>--}}
{{--    <tr style="vertical-align: top">--}}
{{--        <td>Spidol</td>--}}
{{--        <td style="text-align: right">2</td>--}}
{{--        <td style="text-align: right">10.000</td>--}}
{{--    </tr>--}}
{{--    </tbody>--}}

{{--    <tfoot style="border-top: 1px solid black">--}}
{{--    <tr style="vertical-align: top">--}}
{{--        <th colspan="2">--}}
{{--            <img src="data:image/png;base64, {!! base64_encode(QRCode::format('png')->size(100)->generate($qrcode)) !!}" alt="qrcode">--}}
{{--        </th>--}}
{{--    </tr>--}}
{{--    </tfoot>--}}
{{--</table>--}}
<img src="data:image/png;base64, {!! base64_encode(QRCode::format('png')->size(200)->generate($qrcode)) !!}" alt="qrcode" style="width: 100%">


</body>
</html>