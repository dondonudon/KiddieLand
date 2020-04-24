<?php

namespace App\Http\Controllers;

use App\master_seragam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class c_MasterSeragam extends Controller
{
 public function index()
 {
  return view('dashboard.master-data.seragam.baru');
 }

 function list() {
  return view('dashboard.master-data.seragam.list');
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
  return DB::table('master_seragam')
   ->select('id', 'kode', 'nama', 'harga_beli', 'harga_jual', 'harga_beli_akhir', 'tgl_beli_akhir', 'stock', 'satuan', 'keterangan', 'spesifikasi')
  // ->join('ms_koridor','ms_bus.id_koridor','=','ms_koridor.id')
   ->where($data['where'])
   ->paginate(8);
 }

 public function edit($id)
 {
  $data = DB::table('master_seragam')
   ->select('id', 'kode', 'nama', 'harga_beli', 'harga_jual', 'harga_beli_akhir', 'tgl_beli_akhir', 'stock', 'satuan', 'keterangan', 'spesifikasi')
  // ->join('ms_koridor','ms_bus.id_koridor','=','ms_koridor.id')
   ->where('master_seragam.id', '=', $id)
   ->first();
  return view('dashboard.master-data.seragam.edit')->with('data', $data);
 }

 public function submit(Request $request)
 {
  $type        = $request->type;
  $kode        = $request->kode;
  $nama        = $request->nama;
  $harga_beli  = $request->harga_beli;
  $harga_jual  = $request->harga_jual;
  $satuan      = $request->satuan;
  $keterangan  = $request->keterangan;
  $spesifikasi = $request->spesifikasi;
  $date        = date('Y-m-d H:i:s');

  try {
   DB::beginTransaction();
   if ($type == 'baru') {
    $master_seragam              = new master_seragam();
    $master_seragam->kode        = $kode;
    $master_seragam->nama        = $nama;
    $master_seragam->harga_beli  = $harga_beli;
    $master_seragam->harga_jual  = $harga_jual;
    $master_seragam->satuan      = $satuan;
    $master_seragam->keterangan  = $keterangan;
    $master_seragam->spesifikasi = $spesifikasi;
    $master_seragam->created_at  = $date;
    $master_seragam->updated_at  = $date;
    $master_seragam->save();
   } elseif ($type == 'edit') {
    DB::table('master_seragam')
     ->where('id', '=', $request->id)
     ->update([
      'kode'        => $kode,
      'nama'        => $nama,
      'harga_beli'  => $harga_beli,
      'harga_jual'  => $harga_jual,
      'satuan'      => $satuan,
      'keterangan'  => $keterangan,
      'spesifikasi' => $spesifikasi,
      'updated_at'  => $date,
     ]);
   }
   DB::commit();
   return 'success';
  } catch (\Exception $ex) {
   DB::rollBack();
   return response()->json($ex);
  }
 }
}
