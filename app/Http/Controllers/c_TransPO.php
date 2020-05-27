<?php

namespace App\Http\Controllers;

use App\po_mst;
use App\po_trn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class c_TransPO extends Controller
{
 public function index()
 {
  $data = DB::table('master_siswa')
   ->select('id', 'kode_siswa', 'nama')
   ->get();
  return view('dashboard.transaksi.po.baru')->with('data', $data);
 }

 function list() {
  return view('dashboard.transaksi.po.list');
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
  return DB::table('po_mst')
   ->select('id', 'no_po', 'tgl_po', 'supplier', 'ket', 'status')
  // ->join('ms_koridor','ms_bus.id_koridor','=','ms_koridor.id')
   ->where($data['where'])
   ->paginate(8);
 }

 public function submit(Request $request)
 {
  $type         = $request->type;
  $supplier     = $request->supplier;
  $kode_seragam = $request->kode_seragam;
  $date         = date('Y-m-d H:i:s');
  $counter      = DB::table('counter')->where('ket', '=', 'PO')->value('num');
  $no_po        = 'P' . str_pad($counter, 4, '0', STR_PAD_LEFT);
  //   $tgl_po       = date('Y-m-d');

  try {
   DB::beginTransaction();
   if ($type == 'baru') {
    $po_mst             = new po_mst();
    $po_mst->no_po      = $no_po;
    $po_mst->tgl_po     = $date;
    $po_mst->supplier   = $supplier;
    $po_mst->created_at = $date;
    $po_mst->updated_at = $date;

    foreach ($kode_seragam as $kode) {
     $po_trn               = new po_trn();
     $po_trn->no_po        = $no_po;
     $r                    = explode("#", $kode);
     $po_trn->kode_seragam = $r[0];
     $po_trn->qty          = $r[1];
     $po_trn->created_at   = $date;
     $po_trn->updated_at   = $date;
     $po_trn->save();
    }
    $po_mst->save();
    $query = DB::table('counter')
     ->where('ket', '=', 'PO');
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
