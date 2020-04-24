<?php

namespace App\Http\Controllers;

use App\master_kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class c_MasterKelas extends Controller
{
 public function index()
 {
  return view('dashboard.master-data.kelas.baru');
 }

 function list() {
  return view('dashboard.master-data.kelas.list');
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
  return DB::table('master_kelas')
   ->select('id', 'kelas', 'nama', 'adm_fee', 'spp', 'mc', 'ket')
  // ->join('ms_koridor','ms_bus.id_koridor','=','ms_koridor.id')
   ->where($data['where'])
   ->paginate(8);
 }

 public function edit($id)
 {
  $data = DB::table('master_kelas')
   ->select('id', 'kelas', 'nama', 'adm_fee', 'spp', 'mc', 'ket')
  // ->join('ms_koridor','ms_bus.id_koridor','=','ms_koridor.id')
   ->where('master_kelas.id', '=', $id)
   ->first();
  return view('dashboard.master-data.kelas.edit')->with('data', $data);
 }

 public function submit(Request $request)
 {
  $type    = $request->type;
  $kelas   = $request->kelas;
  $nama    = $request->nama;
  $adm_fee = $request->adm_fee;
  $spp     = $request->spp;
  $mc      = $request->mc;
  $ket     = $request->ket;
  $date    = date('Y-m-d H:i:s');

  try {
   DB::beginTransaction();
   if ($type == 'baru') {
    $master_kelas             = new master_kelas();
    $master_kelas->kelas      = $kelas;
    $master_kelas->nama       = $nama;
    $master_kelas->adm_fee    = $adm_fee;
    $master_kelas->spp        = $spp;
    $master_kelas->mc         = $mc;
    $master_kelas->ket        = $ket;
    $master_kelas->created_at = $date;
    $master_kelas->updated_at = $date;
    $master_kelas->save();
   } elseif ($type == 'edit') {
    DB::table('master_kelas')
     ->where('id', '=', $request->id)
     ->update([
      'kelas'      => $kelas,
      'nama'       => $nama,
      'adm_fee'    => $adm_fee,
      'spp'        => $spp,
      'mc'         => $mc,
      'ket'        => $ket,
      'updated_at' => $date,
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
