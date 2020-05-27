<?php

namespace App\Http\Controllers;

use App\master_supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class c_MasterSupplier extends Controller
{
 public function index()
 {
  return view('dashboard.master-data.supplier.baru');
 }

 function list() {
  return view('dashboard.master-data.supplier.list');
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
  return DB::table('master_supplier')
   ->select('id', 'nama', 'alamat', 'kota', 'telp', 'status')
  // ->join('ms_koridor','ms_bus.id_koridor','=','ms_koridor.id')
   ->where($data['where'])
   ->paginate(8);
 }

 public function edit($id)
 {
  $data = DB::table('master_supplier')
   ->select('id', 'nama', 'alamat', 'kota', 'telp', 'status')
  // ->join('ms_koridor','ms_bus.id_koridor','=','ms_koridor.id')
   ->where('master_supplier.id', '=', $id)
   ->first();
  return view('dashboard.master-data.supplier.edit')->with('data', $data);
 }

 public function submit(Request $request)
 {
  $type   = $request->type;
  $nama   = $request->nama;
  $alamat = $request->alamat;
  $kota   = $request->kota;
  $telp   = $request->telp;
  $date   = date('Y-m-d H:i:s');

  try {
   DB::beginTransaction();
   if ($type == 'baru') {
    $master_supplier             = new master_supplier();
    $master_supplier->nama       = $nama;
    $master_supplier->alamat     = $alamat;
    $master_supplier->kota       = $kota;
    $master_supplier->telp       = $telp;
    $master_supplier->status     = '0';
    $master_supplier->created_at = $date;
    $master_supplier->updated_at = $date;
    $master_supplier->save();
   } elseif ($type == 'edit') {
    DB::table('master_supplier')
     ->where('id', '=', $request->id)
     ->update([
      'nama'       => $nama,
      'alamat'     => $alamat,
      'kota'       => $kota,
      'telp'       => $telp,
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
   DB::table('master_supplier')->where('id', '=', $id)
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
   DB::table('master_supplier')->where('id', '=', $id)
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
