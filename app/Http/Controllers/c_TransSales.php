<?php

namespace App\Http\Controllers;

use App\master_seragam;
use App\sales_mst;
use App\sales_trn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class c_TransSales extends Controller
{
 public function index()
 {
  return view('dashboard.transaksi.sales.baru');
 }

 function list() {
  return view('dashboard.transaksi.sales.list');
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
  return DB::table('sales_mst')
   ->select('no_sales', 'tgl')
  // ->join('ms_koridor','ms_bus.id_koridor','=','ms_koridor.id')
   ->where($data['where'])
   ->paginate(8);
 }

 public function submit(Request $request)
 {
  $type         = $request->type;
  $kode_seragam = $request->kode_seragam;
  $date         = date('Y-m-d H:i:s');
  $counter      = DB::table('counter')->where('ket', '=', 'S')->value('num');
  $no_sales     = 'S' . str_pad($counter, 4, '0', STR_PAD_LEFT);
  //   $qty          = $request->qty;
  //   $qty_supply   = $request->qty_supply;
  //   $harga        = $request->harga;

  try {
   DB::beginTransaction();
   if ($type == 'baru') {
    $sales_mst               = new sales_mst();
    $sales_mst->no_sales     = $no_sales;
    $sales_mst->tgl          = $date;
    $sales_mst->total_barang = 0;
    $sales_mst->total_nilai  = 0;
    $sales_mst->ket          = '';
    $sales_mst->created_at   = $date;
    $sales_mst->updated_at   = $date;

    foreach ($kode_seragam as $kode) {
     $sales_trn               = new sales_trn();
     $sales_trn->no_sales     = $no_sales;
     $r                       = explode("#", $kode);
     $sales_trn->kode_siswa   = $r[0];
     $sales_trn->kode_seragam = $r[1];
     $sales_trn->qty          = $r[2];
     $sales_trn->harga        = $r[3];
     $sales_trn->total        = $r[2] * $r[3];
     $sales_trn->created_at   = $date;
     $sales_trn->updated_at   = $date;
     $sales_trn->save();

     $stock     = master_seragam::where('kode', $r[1])->first()->value('stock');
     $stokakhir = $stock - $r[2];

     master_seragam::where('kode', $r[1]) // find your user by their email
      ->limit(1) // optional - to ensure only one record is updated.
      ->update(array('stock' => $stokakhir)); // update the record in the DB.

    }
    $sales_mst->save();
    $query = DB::table('counter')
     ->where('ket', '=', 'S');
    $query->increment('num');
   }
   DB::commit();
   return 'success';
  } catch (\Exception $ex) {
   DB::rollBack();
   return response()->json($ex);
  }

 }
}
