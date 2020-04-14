<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
        return DB::table('master_siswa')
            ->select('id', 'kode_siswa', 'nama', 'alamat', 'tahun_masuk', 'nama_ayah', 'nama_ibu')
        // ->join('ms_koridor','ms_bus.id_koridor','=','ms_koridor.id')
            ->where($data['where'])
            ->paginate(8);
    }

    public function edit($id)
    {
        $data = DB::table('master_siswa')
            ->select('id', 'kode_siswa', 'nama', 'alamat', 'tahun_masuk', 'jenis_kelamin', 'tempat_lahir', 'tgl_lahir', 'agama', 'foto', 'nama_ayah', 'nama_ibu', 'pekerjaan_ayah', 'pekerjaan_ibu', 'email_ortu', 'nohp')
        // ->join('ms_koridor','ms_bus.id_koridor','=','ms_koridor.id')
            ->where('master_siswa.id', '=', $id)
            ->first();
        return view('dashboard.master-data.seragam.edit')->with('data', $data);
    }

    public function submit(Request $request)
    {
        $type           = $request->type;
        $kode_siswa     = $request->kode_siswa;
        $nama           = $request->nama;
        $alamat         = $request->alamat;
        $tahun_masuk    = $request->tahun_masuk;
        $jenkel         = $request->jenkel;
        $tempat_lahir   = $request->tempat_lahir;
        $tgl_lahir      = date('Y-m-d', strtotime($request->tgl_lahir));
        $agama          = $request->agama;
        $nama_ayah      = $request->nama_ayah;
        $nama_ibu       = $request->nama_ibu;
        $pekerjaan_ayah = $request->pekerjaan_ayah;
        $pekerjaan_ibu  = $request->pekerjaan_ibu;
        $email          = $request->email;
        $no_hp          = $request->no_hp;

        try {
            DB::beginTransaction();
            if ($type == 'baru') {
                $master_siswa                 = new master_siswa();
                $master_siswa->kode_siswa     = $kode_siswa;
                $master_siswa->nama           = $nama;
                $master_siswa->alamat         = $alamat;
                $master_siswa->tahun_masuk    = $tahun_masuk;
                $master_siswa->jenis_kelamin  = $jenkel;
                $master_siswa->tempat_lahir   = $tempat_lahir;
                $master_siswa->tgl_lahir      = $tgl_lahir;
                $master_siswa->agama          = $agama;
                $master_siswa->nama_ayah      = $nama_ayah;
                $master_siswa->nama_ibu       = $nama_ibu;
                $master_siswa->pekerjaan_ayah = $pekerjaan_ayah;
                $master_siswa->pekerjaan_ibu  = $pekerjaan_ibu;
                $master_siswa->email_ortu     = $email;
                $master_siswa->nohp           = $no_hp;
                $master_siswa->save();
            } elseif ($type == 'edit') {
                DB::table('master_siswa')
                    ->where('id', '=', $request->id)
                    ->update([
                        'kode_siswa'     => $kode_siswa,
                        'nama'           => $nama,
                        'alamat'         => $alamat,
                        'tahun_masuk'    => $tahun_masuk,
                        'jenis_kelamin'  => $jenkel,
                        'tempat_lahir'   => $tempat_lahir,
                        'tgl_lahir'      => $tgl_lahir,
                        'agama'          => $agama,
                        'nama_ayah'      => $nama_ayah,
                        'nama_ibu'       => $nama_ibu,
                        'pekerjaan_ayah' => $pekerjaan_ayah,
                        'pekerjaan_ibu'  => $pekerjaan_ibu,
                        'email_ortu'     => $email,
                        'nohp'           => $no_hp,

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
