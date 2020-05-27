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

use Illuminate\Support\Facades\Route;

$routes = [
 [
  'method' => 'get',
  'url'    => 'login',
  'act'    => 'c_Login@index',
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
 Route::get('extra', 'c_Dashboard@extra');
 Route::get('siswa', 'c_Dashboard@siswa');
 Route::get('siswa/{kode_siswa}', 'c_Dashboard@siswaID');
 Route::get('thnajaran', 'c_Dashboard@thnajaran');
 Route::get('thnajaran/{id}', 'c_Dashboard@thnajaranID');
 Route::get('csrf', 'c_Dashboard@csrf');
 Route::get('supplier', 'c_Dashboard@supplier');
 Route::get('seragam', 'c_Dashboard@seragam');

 Route::get('/', function () {
  return redirect('dashboard');
 });

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

  Route::get('dashboard/master/kelas', 'c_MasterKelas@index');
  Route::get('dashboard/master/kelas/list', 'c_MasterKelas@list');
  Route::post('dashboard/master/kelas/data', 'c_MasterKelas@data');
  Route::get('dashboard/master/kelas/edit/{id}', 'c_MasterKelas@edit');
  Route::post('dashboard/master/kelas/submit', 'c_MasterKelas@submit');
  Route::post('dashboard/master/kelas/disable', 'c_MasterKelas@disable');
  Route::post('dashboard/master/kelas/activate', 'c_MasterKelas@activate');

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

  Route::get('dashboard/master/seragam', 'c_MasterSeragam@index');
  Route::get('dashboard/master/seragam/list', 'c_MasterSeragam@list');
  Route::post('dashboard/master/seragam/data', 'c_MasterSeragam@data');
  Route::get('dashboard/master/seragam/edit/{id}', 'c_MasterSeragam@edit');
  Route::post('dashboard/master/seragam/submit', 'c_MasterSeragam@submit');
  Route::post('dashboard/master/seragam/disable', 'c_MasterSeragam@disable');
  Route::post('dashboard/master/seragam/activate', 'c_MasterSeragam@activate');

  Route::get('dashboard/master/supplier', 'c_MasterSupplier@index');
  Route::get('dashboard/master/supplier/list', 'c_MasterSupplier@list');
  Route::post('dashboard/master/supplier/data', 'c_MasterSupplier@data');
  Route::get('dashboard/master/supplier/edit/{id}', 'c_MasterSupplier@edit');
  Route::post('dashboard/master/supplier/submit', 'c_MasterSupplier@submit');
  Route::post('dashboard/master/supplier/disable', 'c_MasterSupplier@disable');
  Route::post('dashboard/master/supplier/activate', 'c_MasterSupplier@activate');

  Route::get('dashboard/transaksi/kelas', 'c_TransKelas@index');
  Route::get('dashboard/transaksi/kelas/list', 'c_TransKelas@list');
  Route::post('dashboard/transaksi/kelas/data', 'c_TransKelas@data');
  Route::get('dashboard/transaksi/kelas/edit/{id}', 'c_TransKelas@edit');
  Route::post('dashboard/transaksi/kelas/submit', 'c_TransKelas@submit');
  Route::post('dashboard/transaksi/kelas/disable', 'c_TransKelas@disable');
  Route::post('dashboard/transaksi/kelas/activate', 'c_TransKelas@activate');
  Route::post('dashboard/transaksi/kelas/get_kelas/', 'c_TransKelas@get_kelas');

  Route::get('dashboard/transaksi/extrakurikuler', 'c_TransExtra@index');
  Route::get('dashboard/transaksi/extrakurikuler/list', 'c_TransExtra@list');
  Route::post('dashboard/transaksi/extrakurikuler/data', 'c_TransExtra@data');
  Route::get('dashboard/transaksi/extrakurikuler/edit/{id}', 'c_TransExtra@edit');
  Route::post('dashboard/transaksi/extrakurikuler/submit', 'c_TransExtra@submit');
  Route::post('dashboard/transaksi/extrakurikuler/disable', 'c_TransExtra@disable');
  Route::post('dashboard/transaksi/extrakurikuler/activate', 'c_TransExtra@activate');
  Route::post('dashboard/transaksi/extrakurikuler/get_kelas/', 'c_TransExtra@get_kelas');

  Route::get('dashboard/transaksi/purchaseorder', 'c_TransPO@index');
  Route::get('dashboard/transaksi/purchaseorder/list', 'c_TransPO@list');
  Route::post('dashboard/transaksi/purchaseorder/data', 'c_TransPO@data');
  Route::get('dashboard/transaksi/purchaseorder/edit/{id}', 'c_TransPO@edit');
  Route::post('dashboard/transaksi/purchaseorder/submit', 'c_TransPO@submit');
  Route::post('dashboard/transaksi/purchaseorder/disable', 'c_TransPO@disable');
  Route::post('dashboard/transaksi/purchaseorder/activate', 'c_TransPO@activate');

  Route::get('dashboard/transaksi/receiving', 'c_TransPO@list');
  Route::get('dashboard/transaksi/receiving/supply/{id}', 'c_TransReceiving@new');
  Route::get('dashboard/transaksi/receiving/list', 'c_TransReceiving@list');
  Route::post('dashboard/transaksi/receiving/data', 'c_TransReceiving@data');
  Route::get('dashboard/transaksi/receiving/edit/{id}', 'c_TransReceiving@edit');
  Route::post('dashboard/transaksi/receiving/submit', 'c_TransReceiving@submit');
  Route::post('dashboard/transaksi/receiving/disable', 'c_TransReceiving@disable');
  Route::post('dashboard/transaksi/receiving/activate', 'c_TransReceiving@activate');

  Route::get('dashboard/transaksi/return', 'c_TransReturn@index');
  Route::get('dashboard/transaksi/return/list', 'c_TransReturn@list');
  Route::post('dashboard/transaksi/return/data', 'c_TransReturn@data');
  Route::get('dashboard/transaksi/return/edit/{id}', 'c_TransReturn@edit');
  Route::post('dashboard/transaksi/return/submit', 'c_TransReturn@submit');
  Route::post('dashboard/transaksi/return/disable', 'c_TransReturn@disable');
  Route::post('dashboard/transaksi/return/activate', 'c_TransReturn@activate');

  Route::get('dashboard/transaksi/salesinvoice', 'c_TransSales@index');
  Route::get('dashboard/transaksi/salesinvoice/list', 'c_TransSales@list');
  Route::post('dashboard/transaksi/salesinvoice/data', 'c_TransSales@data');
  Route::get('dashboard/transaksi/salesinvoice/edit/{id}', 'c_TransSales@edit');
  Route::post('dashboard/transaksi/salesinvoice/submit', 'c_TransSales@submit');
  Route::post('dashboard/transaksi/salesinvoice/disable', 'c_TransSales@disable');
  Route::post('dashboard/transaksi/salesinvoice/activate', 'c_TransSales@activate');

  Route::get('dashboard/laporan/biaya', 'c_LaporanBiaya@index');
  Route::post('dashboard/laporan/biaya/data', 'c_LaporanBiaya@data');
  Route::post('dashboard/laporan/biaya/submit', 'c_LaporanBiaya@submit');
  Route::get('dashboard/laporan/biaya/export/pdf/{siswa}', 'c_LaporanBiaya@exportPDF');

  Route::get('dashboard/problem/bus-report', 'c_ProblemBusReport@index');
  Route::post('dashboard/problem/bus-report/data', 'c_ProblemBusReport@data');
  Route::get('dashboard/problem/bus-report/view/{id}', 'c_ProblemBusReport@detail');
 });
});
