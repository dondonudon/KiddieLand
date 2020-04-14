<?php

namespace App\Http\Controllers;

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
}
