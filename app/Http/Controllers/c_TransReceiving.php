<?php

namespace App\Http\Controllers;

use App\master_seragam;
use App\po_mst;
use App\receiving_mst;
use App\receiving_trn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class c_TransReceiving extends Controller
{

 function list() {
  return view('dashboard.transaksi.receiving.list');
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
  return DB::table('receiving_mst')
   ->select('id', 'no_receiving', 'no_po', 'tgl_receiving')
  // ->join('ms_koridor','ms_bus.id_koridor','=','ms_koridor.id')
   ->where($data['where'])
   ->paginate(8);
 }

 function new ($id) {
  $a = DB::table('po_mst')
   ->select('po_mst.id', 'po_mst.tgl_po', 'po_mst.no_po')
   ->where('po_mst.no_po', '=', $id)
   ->first();

  $b = DB::table('po_mst')
   ->select('po_mst.id', 'po_trn.no_po', 'po_mst.tgl_po', 'master_supplier.nama', 'po_trn.kode_seragam', 'master_seragam.nama as nama_seragam', 'po_trn.qty')
   ->join('po_trn', 'po_mst.no_po', '=', 'po_trn.no_po')
   ->join('master_supplier', 'po_mst.supplier', '=', 'master_supplier.id')
   ->join('master_seragam', 'po_trn.kode_seragam', '=', 'master_seragam.kode')
   ->where('po_mst.no_po', '=', $id)
   ->get();

  $c = DB::table('receiving_trn')
   ->select('receiving_trn.kode_seragam', 'receiving_trn.qty', DB::raw('SUM(receiving_trn.qty_supply) as qty_supply'))
   ->join('receiving_mst', 'receiving_mst.no_receiving', '=', 'receiving_trn.no_receiving')
   ->where('receiving_mst.no_po', '=', $id)
   ->groupBy('kode_seragam', 'receiving_trn.qty_supply')
   ->get();

  $data = [
   'mst' => $a,
   'trn' => $b,
   'cek' => $c,
  ];

  return view('dashboard.transaksi.receiving.baru')->with($data);

 }
 public function submit(Request $request)
 {
  $type         = $request->type;
  $no_po        = $request->no_po;
  $kode_seragam = $request->kode_seragam;
  $date         = date('Y-m-d H:i:s');
  $counter      = DB::table('counter')->where('ket', '=', 'R')->value('num');
  $no_receiving = 'R' . str_pad($counter, 4, '0', STR_PAD_LEFT);
  //   $qty          = $request->qty;
  //   $qty_supply   = $request->qty_supply;
  //   $harga        = $request->harga;

  try {
   DB::beginTransaction();
   if ($type == 'baru') {
    $receiving_mst                = new receiving_mst();
    $receiving_mst->no_receiving  = $no_receiving;
    $receiving_mst->no_po         = $no_po;
    $receiving_mst->tgl_receiving = $date;
    $receiving_mst->created_at    = $date;
    $receiving_mst->updated_at    = $date;

    foreach ($kode_seragam as $kode) {
     $receiving_trn               = new receiving_trn();
     $receiving_trn->no_receiving = $no_receiving;
     $r                           = explode("#", $kode);
     $receiving_trn->kode_seragam = $r[0];
     $receiving_trn->qty          = $r[1];
     $receiving_trn->qty_supply   = $r[2];
     $receiving_trn->harga        = $r[3];
     $receiving_trn->created_at   = $date;
     $receiving_trn->updated_at   = $date;
     $receiving_trn->save();

     $stock     = master_seragam::where('kode', $r[0])->first()->value('stock');
     $stokakhir = $stock + $r[2];

     master_seragam::where('kode', $r[0])
      ->limit(1)
      ->update(array(
       'stock'            => $stokakhir,
       'harga_beli_akhir' => $r[3],
       'tgl_beli_akhir'   => $date,
      ));

     po_mst::where('no_po', $no_po)
      ->limit(1)
      ->update(array('status' => 1));

    }
    $receiving_mst->save();
    $query = DB::table('counter')
     ->where('ket', '=', 'R');
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
