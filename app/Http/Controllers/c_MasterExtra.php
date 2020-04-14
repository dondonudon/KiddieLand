<?php

namespace App\Http\Controllers;

use App\master_extrakurikuler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class c_MasterExtra extends Controller
{
 public function index()
 {
  return view('dashboard.master-data.extra.baru');
 }

 function list() {
  return view('dashboard.master-data.extra.list');
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
  return DB::table('master_extrakurikuler')
   ->select('id', 'nama', 'tipe', 'biaya', 'ket')
  // ->join('ms_koridor','ms_bus.id_koridor','=','ms_koridor.id')
   ->where($data['where'])
   ->paginate(8);
 }

 public function edit($id)
 {
  $data = DB::table('master_extrakurikuler')
   ->select('id', 'nama', 'tipe', 'biaya', 'ket')
  // ->join('ms_koridor','ms_bus.id_koridor','=','ms_koridor.id')
   ->where('master_extrakurikuler.id', '=', $id)
   ->first();
  return view('dashboard.master-data.extra.edit')->with('data', $data);
 }

 public function submit(Request $request)
 {
  $type  = $request->type;
  $nama  = $request->nama;
  $tipe  = $request->tipe;
  $biaya = $request->biaya;
  $ket   = $request->ket;

  try {
   DB::beginTransaction();
   if ($type == 'baru') {
    $master_extrakurikuler        = new master_extrakurikuler();
    $master_extrakurikuler->nama  = $nama;
    $master_extrakurikuler->tipe  = $tipe;
    $master_extrakurikuler->biaya = $biaya;
    $master_extrakurikuler->ket   = $ket;
    $master_extrakurikuler->save();
   } elseif ($type == 'edit') {
    DB::table('master_extrakurikuler')
     ->where('id', '=', $request->id)
     ->update([
      'nama'  => $nama,
      'tipe'  => $tipe,
      'biaya' => $biaya,
      'ket'   => $ket,
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
