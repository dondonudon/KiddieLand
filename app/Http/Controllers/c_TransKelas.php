<?php

namespace App\Http\Controllers;

use App\biaya_sekolah;
use App\thn_ajaran_mst;
use App\thn_ajaran_trn;
// use App\master_siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class c_TransKelas extends Controller
{
 public function index()
 {
  $data = DB::table('master_siswa')
   ->select('id', 'kode_siswa', 'nama')
   ->get();
  return view('dashboard.transaksi.kelas.baru')->with('data', $data);
 }

 // public function index()
 // {
 //     return csrf_token();
 // }

 function list() {
  return view('dashboard.transaksi.kelas.list');
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
  return DB::table('thn_ajaran_mst')
   ->select('id', 'kode_ta', 'kode_kelas', 'periode', 'ket')
  // ->join('ms_koridor','ms_bus.id_koridor','=','ms_koridor.id')
   ->where($data['where'])
   ->paginate(8);
 }

 public function edit($id)
 {
  $data = DB::table('thn_ajaran_mst')
   ->select('id', 'kode_guru', 'nama', 'alamat', 'jenis_kelamin', 'tempat_lahir', 'tgl_lahir', 'agama', 'foto', 'jabatan', 'status', 'ket')
  // ->join('ms_koridor','ms_bus.id_koridor','=','ms_koridor.id')
   ->where('thn_ajaran_mst.id', '=', $id)
   ->first();
  return view('dashboard.transaksi.kelas.edit')->with('data', $data);
 }

 public function get_kelas(Request $request)
 {
  $kode_kelas = $request->kode_kelas;
  return DB::table('master_kelas')
   ->select('id', 'kelas as text', 'nama', 'adm_fee', 'spp', 'mc', 'ket')
   ->where('kode_kelas', '=', $kode_kelas)
   ->orderBy('id', 'asc')
   ->get();
 }

 public function submit(Request $request)
 {
  $type         = $request->type;
  $kode_ta      = $request->kode_ta;
  $kode_kelas   = $request->kelas;
  $tahun_ajaran = $request->tahun_ajaran;
  $kode_siswa   = $request->kode_siswa;
//   $result       = DB::table('master_kelas')->select('adm_fee', 'spp', 'mc')->where('id', '=', $kode_kelas)->get();
  $adm_fee = DB::table('master_kelas')->where('id', '=', $kode_kelas)->value('adm_fee');
  $spp     = DB::table('master_kelas')->where('id', '=', $kode_kelas)->value('spp');
  $mc      = DB::table('master_kelas')->where('id', '=', $kode_kelas)->value('mc');
  $periode = DB::table('master_thn_ajaran')->where('id', '=', $tahun_ajaran)->value('periode');

  try {
   DB::beginTransaction();
   if ($type == 'baru') {
    if (DB::table('thn_ajaran_mst')->where('kode_ta', '=', $kode_ta)->doesntExist()) {
     $thn_ajaran_mst             = new thn_ajaran_mst();
     $thn_ajaran_mst->kode_ta    = $kode_ta;
     $thn_ajaran_mst->kode_kelas = $kode_kelas;
     $thn_ajaran_mst->periode    = $tahun_ajaran;
     $thn_ajaran_mst->save();

     foreach ($kode_siswa as $s) {

      $thn_ajaran_trn = new thn_ajaran_trn();

      $thn_ajaran_trn->kode_ta = $kode_ta;
      $thn_ajaran_trn->adm_fee = $adm_fee;
      //   $thn_ajaran_trn->spp        = $spp;
      //   $thn_ajaran_trn->mc         = $mc;
      $r                          = explode("#", $s);
      $thn_ajaran_trn->kode_siswa = $r[0];
      $thn_ajaran_trn->tipe       = $r[1];
      if ($r[1] == 1) {
       $thn_ajaran_trn->spp = $spp;
       $thn_ajaran_trn->mc  = $mc;

       $biaya_sekolah             = new biaya_sekolah();
       $biaya_sekolah->kode       = '1';
       $biaya_sekolah->kode_siswa = $r[0];
       $biaya_sekolah->tahun      = $periode;
       $biaya_sekolah->bulan      = '1';
       $biaya_sekolah->biaya      = 'adm_fee';
       $biaya_sekolah->nilai      = $adm_fee;
       $biaya_sekolah->save();

       for ($i = 1; $i < 2; $i++) {
        $biaya_sekolah             = new biaya_sekolah();
        $biaya_sekolah->kode       = '1';
        $biaya_sekolah->kode_siswa = $r[0];
        $biaya_sekolah->tahun      = $periode;
        $biaya_sekolah->bulan      = '1';
        $biaya_sekolah->biaya      = 'mc';
        $biaya_sekolah->nilai      = $mc;
        $biaya_sekolah->save();
       }

       for ($i = 1; $i < 2; $i++) {
        $biaya_sekolah             = new biaya_sekolah();
        $biaya_sekolah->kode       = '1';
        $biaya_sekolah->kode_siswa = $r[0];
        $biaya_sekolah->tahun      = $periode;
        $biaya_sekolah->bulan      = '1';
        $biaya_sekolah->biaya      = 'spp';
        $biaya_sekolah->nilai      = $spp;
        $biaya_sekolah->save();
       }

      } elseif ($r[1] == 2) {
       $thn_ajaran_trn->spp = $spp;
       $thn_ajaran_trn->mc  = $mc;

       $biaya_sekolah             = new biaya_sekolah();
       $biaya_sekolah->kode       = '1';
       $biaya_sekolah->kode_siswa = $r[0];
       $biaya_sekolah->tahun      = $periode;
       $biaya_sekolah->bulan      = '1';
       $biaya_sekolah->biaya      = 'adm_fee';
       $biaya_sekolah->nilai      = $adm_fee;
       $biaya_sekolah->save();

       for ($i = 1; $i < 13; $i++) {
        $biaya_sekolah             = new biaya_sekolah();
        $biaya_sekolah->kode       = '1';
        $biaya_sekolah->kode_siswa = $r[0];
        $biaya_sekolah->tahun      = $periode;
        $biaya_sekolah->bulan      = $i;
        $biaya_sekolah->biaya      = 'mc';
        $biaya_sekolah->nilai      = $mc / 12;
        $biaya_sekolah->save();
       }

       for ($i = 1; $i < 13; $i++) {
        $biaya_sekolah             = new biaya_sekolah();
        $biaya_sekolah->kode       = '1';
        $biaya_sekolah->kode_siswa = $r[0];
        $biaya_sekolah->tahun      = $periode;
        $biaya_sekolah->bulan      = $i;
        $biaya_sekolah->biaya      = 'spp';
        $biaya_sekolah->nilai      = $spp / 12;
        $biaya_sekolah->save();
       }

      } elseif ($r[1] == 3) {
       $thn_ajaran_trn->spp = $spp;
       $thn_ajaran_trn->mc  = $mc;

       $biaya_sekolah             = new biaya_sekolah();
       $biaya_sekolah->kode       = '1';
       $biaya_sekolah->kode_siswa = $r[0];
       $biaya_sekolah->tahun      = $periode;
       $biaya_sekolah->bulan      = '1';
       $biaya_sekolah->biaya      = 'adm_fee';
       $biaya_sekolah->nilai      = $adm_fee;
       $biaya_sekolah->save();

       for ($i = 1; $i < 13; $i += 3) {
        $biaya_sekolah             = new biaya_sekolah();
        $biaya_sekolah->kode       = '1';
        $biaya_sekolah->kode_siswa = $r[0];
        $biaya_sekolah->tahun      = $periode;
        $biaya_sekolah->bulan      = $i;
        $biaya_sekolah->biaya      = 'mc';
        $biaya_sekolah->nilai      = $mc / 4;
        $biaya_sekolah->save();
       }

       for ($i = 1; $i < 13; $i += 3) {
        $biaya_sekolah             = new biaya_sekolah();
        $biaya_sekolah->kode       = '1';
        $biaya_sekolah->kode_siswa = $r[0];
        $biaya_sekolah->tahun      = $periode;
        $biaya_sekolah->bulan      = $i;
        $biaya_sekolah->biaya      = 'spp';
        $biaya_sekolah->nilai      = $spp / 4;
        $biaya_sekolah->save();
       }
      }

      $thn_ajaran_trn->save();

     }
    } else {
     return response()->json($ex);
    }

   } elseif ($type == 'edit') {
    DB::table('thn_ajaran_mst')
     ->where('id', '=', $request->id)
     ->update([
      'kode_ta'    => $kode_ta,
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
