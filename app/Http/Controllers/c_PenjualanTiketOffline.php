<?php

namespace App\Http\Controllers;

use App\msPenumpang;
use App\transaksi;
use App\msTiket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class c_PenjualanTiketOffline extends Controller
{
    public function index() {
        return view('dashboard.penjualan.tiket-offline.baru');
    }

    public function submit(Request $request) {
        $user = $request->petugas;
        $koridor = $request->koridor;
        $bus = $request->bus;
        $shift = $request->shift;
        $penumpang = $request->penumpang;
        $start = $request->start_tiket;
        $end = $request->end_tiket;
        $kode_tiket = $request->kode_tiket;
        $sisa = $request->sisa_tiket_;
        $dtPenumpang = msPenumpang::find($penumpang);
        try {
            DB::beginTransaction();
            for ($x=$start; $x<=$end; $x++) {
                $trn = new transaksi();
                $trn->no_transaksi = $x;
                $trn->tgl_transaksi = date('Y-m-d');
                $trn->jam_transaksi = date('H:i:s');
                $trn->id_penumpang = $penumpang;
                $trn->id_koridor = $koridor;
                $trn->id_bus = $bus;
                $trn->id_shelter = 0;
                $trn->trip_a = '';
                $trn->trip_b = '';
                $trn->shift = $shift;
                $trn->username = $user;
                $trn->opsi_bayar = 0;
                $trn->harga = $dtPenumpang->harga;
                $trn->save();
            }
            DB::table('ms_tiket')->where('kode','=',$kode_tiket)
            ->update([
                'sisa' => $sisa
            ]);
            DB::commit();
            return 'success';
        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->json($ex);
        }
    }
}
