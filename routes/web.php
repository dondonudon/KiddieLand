<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

$routes = [
    [
        'method' => 'get',
        'url' => 'login',
        'act' => 'c_Login@index'
    ],
];

foreach ($routes as $route) {
    if ($route['method'] == 'get') {
        Route::get($route['url'], $route['act'])->middleware();
    }
}

//Route::get('print/{text}',function ($text) {
//    $data['system'] = 'Trans Jateng';
//    $data['qrcode'] = $text;
//    $pdf = PDF::loadView('dashboard.print',$data)->setPaper(array(0,0,200,164),'landscape');
//    return $pdf->stream(date('Ymd').'.pdf');
//});
Route::get('payment/gopay', function () {
    return view('dashboard.gopay');
});
//Route::get('generate/qrcode/png/{text}',function ($text) {
//    $image = QRCode::format('png')
//        ->merge('assets/logo/without-name/logo-1000.png', 0.5, true)
//        ->size(500)->errorCorrection('H')
//        ->generate($text);
//    return response($image)->header('Content-type','image/png');
//});
//Route::get('generate/qrcode/svg/{text}',function ($text) {
//    $image = QRCode::format('svg')
//        ->size(500)->errorCorrection('H')
//        ->generate($text);
//    return response($image)->header('Content-type','image/svg+xml');
//});

//Route::get('login','c_Login@index');
Route::post('login/submit', 'c_Login@submit');
Route::post('logout', 'c_Login@logout');
Route::get('reset-password', 'c_Login@resetPassword');
Route::post('reset-password/submit', 'c_Login@resetPasswordSubmit');
Route::get('storage/{filename}', function ($filename) {
    return response()->file(storage_path('app/public/' . $filename));
});

Route::middleware(['check.login'])->group(function () {
    // Route::get('penumpang', 'c_Dashboard@penumpang');
    // Route::get('penumpang/{id}', 'c_Dashboard@penumpangID');
    // Route::get('tiket', 'c_Dashboard@tiket');
    // Route::get('tiket/{id}', 'c_Dashboard@tiketID');

    Route::get('kelas', 'c_Dashboard@kelas');
    Route::get('thnajaran', 'c_Dashboard@thnajaran');

    Route::get('/', function () {
        return redirect('dashboard');
    });

    Route::post('dashboard/statistics', 'c_Overview@statistics');
    Route::post('dashboard/koridor-location', 'c_Overview@koridorLocation');

    Route::get('dashboard', 'c_Overview@index');
    Route::get('dashboard/profile', 'c_Profile@edit');
    Route::post('dashboard/profile/submit', 'c_Profile@submit');

    Route::middleware(['menu.permission'])->group(function () {
        Route::get('dashboard/system/menu', 'c_SysMenu@index');

        Route::get('dashboard/system/menu-group', 'c_SysMenuGroup@index');
        Route::get('dashboard/system/menu-group/list', 'c_SysMenuGroup@list');
        Route::get('dashboard/system/menu-group/list/data', 'c_SysMenuGroup@listData');
        Route::post('dashboard/system/menu-group/submit', 'c_SysMenuGroup@submit');
        Route::get('dashboard/system/menu-group/edit/{id}', 'c_SysMenuGroup@edit');

        Route::get('dashboard/system/menu', 'c_SysMenu@index');
        Route::get('dashboard/system/menu/list', 'c_SysMenu@list');
        Route::post('dashboard/system/menu/list/data', 'c_SysMenu@listData');
        Route::post('dashboard/system/menu/submit', 'c_SysMenu@submit');
        Route::get('dashboard/system/menu/edit/{id}', 'c_SysMenu@edit');
        Route::post('dashboard/system/menu/reorder', 'c_SysMenu@reorder');

        Route::get('dashboard/master/user-management', 'c_MasterUserManagement@index');
        Route::get('dashboard/master/user-management/list', 'c_MasterUserManagement@list');
        Route::post('dashboard/master/user-management/data', 'c_MasterUserManagement@data');
        Route::get('dashboard/master/user-management/edit/{username}', 'c_MasterUserManagement@edit');
        Route::post('dashboard/master/user-management/submit', 'c_MasterUserManagement@submit');
        Route::post('dashboard/master/user-management/reset-password', 'c_MasterUserManagement@resetPassword');
        Route::post('dashboard/master/user-management/disable', 'c_MasterUserManagement@disable');
        Route::post('dashboard/master/user-management/activate', 'c_MasterUserManagement@activate');

        Route::get('dashboard/master/siswa', 'c_MasterSiswa@index');
        Route::get('dashboard/master/siswa/list', 'c_MasterSiswa@list');
        Route::post('dashboard/master/siswa/data', 'c_MasterSiswa@data');
        Route::get('dashboard/master/siswa/edit/{id}', 'c_MasterSiswa@edit');
        Route::post('dashboard/master/siswa/submit', 'c_MasterSiswa@submit');
        Route::post('dashboard/master/siswa/disable', 'c_MasterSiswa@disable');
        Route::post('dashboard/master/siswa/activate', 'c_MasterSiswa@activate');

        Route::get('dashboard/master/guru', 'c_MasterGuru@index');
        Route::get('dashboard/master/guru/list', 'c_MasterGuru@list');
        Route::post('dashboard/master/guru/data', 'c_MasterGuru@data');
        Route::get('dashboard/master/guru/edit/{id}', 'c_MasterGuru@edit');
        Route::post('dashboard/master/guru/submit', 'c_MasterGuru@submit');
        Route::post('dashboard/master/guru/disable', 'c_MasterGuru@disable');
        Route::post('dashboard/master/guru/activate', 'c_MasterGuru@activate');

        Route::get('dashboard/master/pegawai', 'c_MasterPegawai@index');
        Route::get('dashboard/master/pegawai/list', 'c_MasterPegawai@list');
        Route::post('dashboard/master/pegawai/data', 'c_MasterPegawai@data');
        Route::get('dashboard/master/pegawai/edit/{id}', 'c_MasterPegawai@edit');
        Route::post('dashboard/master/pegawai/submit', 'c_MasterPegawai@submit');
        Route::post('dashboard/master/pegawai/disable', 'c_MasterPegawai@disable');
        Route::post('dashboard/master/pegawai/activate', 'c_MasterPegawai@activate');

        Route::get('dashboard/master/kelas', 'c_MasterKelass@index');
        Route::get('dashboard/master/kelas/list', 'c_MasterKelass@list');
        Route::post('dashboard/master/kelas/data', 'c_MasterKelass@data');
        Route::get('dashboard/master/kelas/edit/{id}', 'c_MasterKelass@edit');
        Route::post('dashboard/master/kelas/submit', 'c_MasterKelass@submit');
        Route::post('dashboard/master/kelas/disable', 'c_MasterKelass@disable');
        Route::post('dashboard/master/kelas/activate', 'c_MasterKelass@activate');

        Route::get('dashboard/master/tahun-ajaran', 'c_MasterTA@index');
        Route::get('dashboard/master/tahun-ajaran/list', 'c_MasterTA@list');
        Route::post('dashboard/master/tahun-ajaran/data', 'c_MasterTA@data');
        Route::get('dashboard/master/tahun-ajaran/edit/{id}', 'c_MasterTA@edit');
        Route::post('dashboard/master/tahun-ajaran/submit', 'c_MasterTA@submit');
        Route::post('dashboard/master/tahun-ajaran/disable', 'c_MasterTA@disable');
        Route::post('dashboard/master/tahun-ajaran/activate', 'c_MasterTA@activate');

        Route::get('dashboard/master/extrakurikuler', 'c_MasterExtra@index');
        Route::get('dashboard/master/extrakurikuler/list', 'c_MasterExtra@list');
        Route::post('dashboard/master/extrakurikuler/data', 'c_MasterExtra@data');
        Route::get('dashboard/master/extrakurikuler/edit/{id}', 'c_MasterExtra@edit');
        Route::post('dashboard/master/extrakurikuler/submit', 'c_MasterExtra@submit');
        Route::post('dashboard/master/extrakurikuler/disable', 'c_MasterExtra@disable');
        Route::post('dashboard/master/extrakurikuler/activate', 'c_MasterExtra@activate');

        Route::get('dashboard/transaksi/kelas', 'c_MasterKelas@index');
        Route::get('dashboard/transaksi/kelas/list', 'c_MasterKelas@list');
        Route::post('dashboard/transaksi/kelas/data', 'c_MasterKelas@data');
        Route::get('dashboard/transaksi/kelas/edit/{id}', 'c_MasterKelas@edit');
        Route::post('dashboard/transaksi/kelas/submit', 'c_MasterKelas@submit');
        Route::post('dashboard/transaksi/kelas/disable', 'c_MasterKelas@disable');
        Route::post('dashboard/transaksi/kelas/activate', 'c_MasterKelas@activate');
        Route::post('dashboard/transaksi/kelas/get_kelas/', 'c_MasterKelas@get_kelas');

        Route::get('dashboard/laporan/monitoring-penumpang', 'c_MonitoringPenumpang@index');
        Route::post('dashboard/laporan/monitoring-penumpang/data', 'c_MonitoringPenumpang@data');
        Route::get('dashboard/laporan/monitoring-penumpang/export/pdfpagi/{tgl}', 'c_MonitoringPenumpang@exportPDFpagi');
        Route::get('dashboard/laporan/monitoring-penumpang/export/pdfsore/{tgl}', 'c_MonitoringPenumpang@exportPDFsore');

        Route::get('dashboard/laporan/transaksi-petugas', 'c_LaporanTransaksiPetugas@index');
        Route::post('dashboard/laporan/transaksi-petugas/data', 'c_LaporanTransaksiPetugas@data');
        Route::get('dashboard/laporan/transaksi-petugas/export/pdf/{tgl}', 'c_LaporanTransaksiPetugas@exportPDF');
        Route::get('dashboard/laporan/transaksi-petugas/export-detail/pdf/{tgl}/{username}', 'c_LaporanTransaksiPetugas@exportDetailPetugasPDF');

        Route::get('dashboard/laporan/top-transaksi-petugas', 'c_LaporanTopTransaksiPetugas@index');
        Route::post('dashboard/laporan/top-transaksi-petugas/data', 'c_LaporanTopTransaksiPetugas@data');
        Route::get('dashboard/laporan/top-transaksi-petugas/export/pdf/{tgl}', 'c_LaporanTopTransaksiPetugas@exportPDF');

        Route::get('dashboard/laporan/transaksi-per-jenis', 'c_LaporanTransaksiPerJenis@index');
        Route::post('dashboard/laporan/transaksi-per-jenis/data', 'c_LaporanTransaksiPerJenis@data');
        Route::post('dashboard/laporan/transaksi-per-jenis/data-offline', 'c_LaporanTransaksiPerJenis@dataOffline');
        Route::get('dashboard/laporan/transaksi-per-jenis/export/pdf/{tgl}', 'c_LaporanTransaksiPerJenis@exportPDF');

        Route::get('dashboard/laporan/transaksi-per-koridor', 'c_LaporanTransaksiPerKoridor@index');
        Route::post('dashboard/laporan/transaksi-per-koridor/data', 'c_LaporanTransaksiPerKoridor@data');
        Route::get('dashboard/laporan/transaksi-per-koridor/export/pdf/{tgl}', 'c_LaporanTransaksiPerKoridor@exportPDF');

        Route::get('dashboard/laporan/transaksi-bus-shelter', 'c_LaporanTransaksiBusShelter@index');
        Route::post('dashboard/laporan/transaksi-bus-shelter/data', 'c_LaporanTransaksiBusShelter@data');
        Route::get('dashboard/laporan/transaksi-bus-shelter/export/pdf/{tgl}', 'c_LaporanTransaksiBusShelter@exportPDF');

        Route::get('dashboard/penjualan/tiket-offline', 'c_PenjualanTiketOffline@index');
        Route::post('dashboard/penjualan/tiket-offline/submit', 'c_PenjualanTiketOffline@submit');

        Route::get('dashboard/problem/bus-report', 'c_ProblemBusReport@index');
        Route::post('dashboard/problem/bus-report/data', 'c_ProblemBusReport@data');
        Route::get('dashboard/problem/bus-report/view/{id}', 'c_ProblemBusReport@detail');
    });
});
