<?php

namespace App\Http\Controllers;

use App\master_guru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class c_MasterPegawai extends Controller
{
    public function index()
    {
        return view('dashboard.master-data.pegawai.baru');
    }

    public function list()
    {
        return view('dashboard.master-data.pegawai.list');
    }

    public function data(Request $request)
    {
        $filters = $request->filters;
        $data = [
            'where' => ['status' => '2']
        ];
        if ($filters !== null) {
            foreach ($filters as $f) {
                $data['where'][] = [
                    $f['field'], $f['type'], '%' . $f['value'] . '%'
                ];
            }
        }
        return DB::table('master_guru')
            ->select('id', 'kode_guru', 'nama', 'alamat', 'tempat_lahir', 'tgl_lahir')
            // ->join('ms_koridor','ms_bus.id_koridor','=','ms_koridor.id')
            ->where($data['where'])
            ->paginate(8);
    }

    public function edit($id)
    {
        $data = DB::table('master_guru')
            ->select('id', 'kode_guru', 'nama', 'alamat', 'jenis_kelamin', 'tempat_lahir', 'tgl_lahir', 'agama', 'foto', 'jabatan', 'status', 'ket')
            // ->join('ms_koridor','ms_bus.id_koridor','=','ms_koridor.id')
            ->where('master_guru.id', '=', $id)
            ->first();
        return view('dashboard.master-data.pegawai.edit')->with('data', $data);
    }

    public function submit(Request $request)
    {
        $type = $request->type;
        $kode_guru = $request->kode_guru;
        $nama = $request->nama;
        $alamat = $request->alamat;
        $jenkel = $request->jenkel;
        $tempat_lahir = $request->tempat_lahir;
        $tgl_lahir = date('Y-m-d', strtotime($request->tgl_lahir));
        $agama = $request->agama;
        $jabatan = $request->jabatan;
        $ket = $request->keterangan;

        try {
            DB::beginTransaction();
            if ($type == 'baru') {
                $master_guru = new master_guru();
                $master_guru->kode_guru = $kode_guru;
                $master_guru->nama = $nama;
                $master_guru->alamat = $alamat;
                $master_guru->jenis_kelamin = $jenkel;
                $master_guru->tempat_lahir = $tempat_lahir;
                $master_guru->tgl_lahir = $tgl_lahir;
                $master_guru->agama = $agama;
                $master_guru->jabatan = $jabatan;
                $master_guru->ket = $ket;
                $master_guru->status = '2';
                $master_guru->save();
            } elseif ($type == 'edit') {
                DB::table('master_guru')
                    ->where('id', '=', $request->id)
                    ->update([
                        'kode_guru' => $kode_guru,
                        'nama' => $nama,
                        'alamat' => $alamat,
                        'jenis_kelamin' => $jenkel,
                        'tempat_lahir' => $tempat_lahir,
                        'tgl_lahir' => $tgl_lahir,
                        'agama' => $agama,
                        'jabatan' => $jabatan,
                        'ket' => $ket,
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
                    'status' => 0
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
                    'status' => 1
                ]);
            DB::commit();
            return 'success';
        } catch (\Exception $ex) {
            DB::rollBack();
            return json_encode([$ex]);
        }
    }
}
