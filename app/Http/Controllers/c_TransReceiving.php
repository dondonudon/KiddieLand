<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class c_TransReceiving extends Controller
{
    public function index()
    {
        $data = DB::table('master_siswa')
        ->select('id', 'kode_siswa', 'nama')
        ->get();
        return view('dashboard.transaksi.receiving.baru')->with('data', $data);
    }
}
