<?php

namespace App\Http\Controllers;

use App\biaya_sekolah;
use App\extra_mst;
use App\extra_trn;
// use App\master_siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class c_TransExtra extends Controller
{
 public function index()
 {
  $data = DB::table('master_siswa')
   ->select('id', 'kode_siswa', 'nama')
   ->get();
  return view('dashboard.transaksi.extra.baru')->with('data', $data);
 }

 // public function index()
 // {
 //     return csrf_token();
 // }

 function list() {
  return view('dashboard.transaksi.extra.list');
 }

 public function data(Request $request)
 {
  $filters = $request->filters;
  $data    = [
   'where' => [],
  ];
  if ($filters !== null) {
   foreach ($filters as $f) {
    $data['where'][] = [
     $f['field'], $f['type'], '%' . $f['value'] . '%',
    ];
   }
  }
  return DB::table('extra_mst')
   ->select('id', 'extra', 'kode_kelas', 'periode', 'ket')
  // ->join('ms_koridor','ms_bus.id_koridor','=','ms_koridor.id')
   ->where($data['where'])
   ->paginate(8);
 }

 public function edit($id)
 {
  $data = DB::table('extra_mst')
   ->select('id', 'kode_guru', 'nama', 'alamat', 'jenis_kelamin', 'tempat_lahir', 'tgl_lahir', 'agama', 'foto', 'jabatan', 'status', 'ket')
  // ->join('ms_koridor','ms_bus.id_koridor','=','ms_koridor.id')
   ->where('extra_mst.id', '=', $id)
   ->first();
  return view('dashboard.transaksi.extra.edit')->with('data', $data);
 }

 public function get_kelas(Request $request)
 {
  $kode_kelas = $request->kode_kelas;
  return DB::table('master_kelas')
   ->select('id', 'kelas as text', 'nama', 'biaya', 'spp', 'mc', 'ket')
   ->where('kode_kelas', '=', $kode_kelas)
   ->orderBy('id', 'asc')
   ->get();
 }

 public function submit(Request $request)
 {
  $type         = $request->type;
  $extra        = $request->extra;
  $kode_kelas   = $request->kelas;
  $tahun_ajaran = $request->tahun_ajaran;
  $kode_siswa   = $request->kode_siswa;
//   $result       = DB::table('master_kelas')->select('biaya', 'spp', 'mc')->where('id', '=', $kode_kelas)->get();
  $biaya   = DB::table('master_extrakurikuler')->where('id', '=', $extra)->value('biaya');
  $periode = DB::table('master_thn_ajaran')->where('id', '=', $tahun_ajaran)->value('periode');

  try {
   DB::beginTransaction();
   if ($type == 'baru') {
    if (DB::table('extra_mst')->where('extra', '=', $extra)->doesntExist()) {
     $extra_mst             = new extra_mst();
     $extra_mst->extra      = $extra;
     $extra_mst->kode_kelas = $kode_kelas;
     $extra_mst->periode    = $tahun_ajaran;
     $extra_mst->save();

     foreach ($kode_siswa as $s) {

      $extra_trn             = new extra_trn();
      $extra_trn->extra      = $extra;
      $extra_trn->biaya      = $biaya;
      $r                     = explode("#", $s);
      $extra_trn->kode_siswa = $r[0];
      $extra_trn->tipe       = $r[1];
      if ($r[1] == 1) {
       for ($i = 1; $i < 2; $i++) {
        $biaya_sekolah             = new biaya_sekolah();
        $biaya_sekolah->kode       = '2';
        $biaya_sekolah->kode_siswa = $r[0];
        $biaya_sekolah->tahun      = $periode;
        $biaya_sekolah->bulan      = '1';
        $biaya_sekolah->biaya      = $extra;
        $biaya_sekolah->nilai      = $biaya;
        $biaya_sekolah->save();
       }
      } elseif ($r[1] == 2) {
       for ($i = 1; $i < 13; $i++) {
        $biaya_sekolah             = new biaya_sekolah();
        $biaya_sekolah->kode       = '2';
        $biaya_sekolah->kode_siswa = $r[0];
        $biaya_sekolah->tahun      = $periode;
        $biaya_sekolah->bulan      = '1';
        $biaya_sekolah->biaya      = $extra;
        $biaya_sekolah->nilai      = $biaya / 12;
        $biaya_sekolah->save();
       }
      } elseif ($r[1] == 3) {
       for ($i = 1; $i < 13; $i += 3) {
        $biaya_sekolah             = new biaya_sekolah();
        $biaya_sekolah->kode       = '2';
        $biaya_sekolah->kode_siswa = $r[0];
        $biaya_sekolah->tahun      = $periode;
        $biaya_sekolah->bulan      = '1';
        $biaya_sekolah->biaya      = $extra;
        $biaya_sekolah->nilai      = $biaya / 4;
        $biaya_sekolah->save();
       }
      }
      $extra_trn->save();

     }
    } else {
     return response()->json($ex);
    }

   } elseif ($type == 'edit') {
    DB::table('extra_mst')
     ->where('id', '=', $request->id)
     ->update([
      'extra'      => $extra,
      'kode_kelas' => $kode_kelas,
      'periode'    => $tahun_ajaran,
     ]);
   }
   DB::commit();
   return 'success';
  } catch (\Exception $ex) {
   DB::rollBack();
   return response()->json($ex);
  }
 }

 public function disable(Request $request)
 {
  $id = $request->id;
  try {
   DB::beginTransaction();
   DB::table('ms_bus')->where('id', '=', $id)
    ->update([
     'status' => 0,
    ]);
   DB::commit();
   return 'success';
  } catch (\Exception $ex) {
   DB::rollBack();
   return json_encode([$ex]);
  }
 }

 public function activate(Request $request)
 {
  $id = $request->id;
  try {
   DB::beginTransaction();
   DB::table('ms_bus')->where('id', '=', $id)
    ->update([
     'status' => 1,
    ]);
   DB::commit();
   return 'success';
  } catch (\Exception $ex) {
   DB::rollBack();
   return json_encode([$ex]);
  }
 }
}
