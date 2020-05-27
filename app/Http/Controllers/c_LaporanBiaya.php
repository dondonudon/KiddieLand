<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;

class c_LaporanBiaya extends Controller
{
 /*
  * Dataset
  */
 public function dataset($siswa)
 {
  try {
   return DB::table('biaya_sekolah')
    ->select('kode_siswa', 'tahun', 'bulan', 'biaya', 'nilai')
    ->where('kode_siswa', '=', $siswa)
    ->get();
  } catch (\Exception $ex) {
   return response()->json([$ex]);
  }
 }

 public function index()
 {
  return view('dashboard.laporan.biaya.index');
 }

 public function data(Request $request)
 {
  $siswa = $request->siswa;
  try {
   return $this->dataset($siswa);
  } catch (\Exception $ex) {
   return response()->json([$ex]);
  }
 }

 public function submit(Request $request)
 {
  $siswa = $request->siswa;
  try {
   return DB::table('biaya_sekolah')
    ->select('kode_siswa', 'tahun', 'bulan', 'biaya', 'nilai')
    ->where('kode_siswa', '=', $siswa)
    ->get();
  } catch (\Exception $ex) {
   return response()->json([$ex]);
  }
 }

 public function exportPDF($siswa)
 {
  try {
   $trn['data'] = DB::table('biaya_sekolah')
    ->select('kode_siswa', 'tahun', 'bulan', 'biaya', 'nilai')
    ->where('kode_siswa', '=', $siswa)
    ->get();

   $pdf = PDF::loadView('dashboard.laporan.biaya.pdf', $trn)->setPaper('a4', 'portrait');
   return $pdf->stream('biaya-periode.pdf');
  } catch (\Exception $ex) {
   return response()->json($ex);
  }
 }

//  public function exportDetailPetugasPDF($tgl, $username)
 //  {
 //   try {
 //    $trn['data'] = DB::table('transaksi')
 //     ->select('transaksi.no_transaksi', 'transaksi.jam_transaksi', 'ms_penumpang.jenis as penumpang', 'ms_pembayaran.nama as pembayaran',
 //      DB::raw('CONCAT(ms_bus.nama," - ",ms_koridor.koridor) AS bus'),
 //      'transaksi.harga'
 //     )
 //     ->leftJoin('ms_penumpang', 'transaksi.id_penumpang', '=', 'ms_penumpang.id')
 //     ->leftJoin('ms_bus', 'transaksi.id_bus', '=', 'ms_bus.id')
 //     ->leftJoin('ms_koridor', 'ms_bus.id_koridor', '=', 'ms_koridor.id')
 //     ->leftJoin('ms_pembayaran', 'transaksi.opsi_bayar', '=', 'ms_pembayaran.id')
 //     ->where([
 //      ['username', '=', $username],
 //      ['tgl_transaksi', '=', $tgl],
 //     ])->get();
 //    $trn['user'] = DB::table('users')
 //     ->where('username', '=', $username)
 //     ->first();
 //    $pdf = PDF::loadView('dashboard.laporan.biaya.pdf-detail', $trn)
 //     ->setPaper('a4', 'portrait');
 //    return $pdf->stream('laporan-transaksi-petugas.pdf');
 //   } catch (\Exception $ex) {
 //    dd('Exception Block', $ex);
 //   }
 //  }
}
