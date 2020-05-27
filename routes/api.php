<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('sync/master','c_Android@syncMaster');

Route::get('koridor','c_Android@datasetKoridor');

Route::get('shelter','c_Android@datasetShelter');

Route::get('bus','c_Android@datasetBus');

Route::get('penumpang','c_Android@webPenumpang');
Route::get('penumpang/{id}',function ($id) {
    $dtPenumpang = DB::table('ms_penumpang')
        ->select('harga')
        ->where('id','=',$id)
        ->where('status','=','1')
        ->first();
    return $dtPenumpang->harga;
});

Route::post('login','c_Android@login');
Route::post('sync','c_Android@sync');
Route::post('problem','c_Android@problemReport');
Route::post('rekap-transaksi','c_Android@rekapTransaksi');
Route::post('laporan-lmb','c_Android@lmbReport');
Route::post('addPenumpangTurun', 'c_Android@insertPenumpangTurun');
