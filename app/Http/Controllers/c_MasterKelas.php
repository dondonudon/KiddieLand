<?php

namespace App\Http\Controllers;

use App\thn_ajaran_mst;
use App\thn_ajaran_trn;
// use App\master_siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class c_MasterKelas extends Controller
{
    public function index()
    {
        $data = DB::table('master_siswa')
            ->select('id', 'kode_siswa', 'nama')
            ->get();
        return view('dashboard.transaksi.kelas.baru')->with('data', $data);
    }

    // public function index()
    // {
    //     return csrf_token();
    // }

    public function list()
    {
        return view('dashboard.transaksi.kelas.list');
    }

    public function data(Request $request)
    {
        $filters = $request->filters;
        $data = [
            'where' => []
        ];
        if ($filters !== null) {
            foreach ($filters as $f) {
                $data['where'][] = [
                    $f['field'], $f['type'], '%' . $f['value'] . '%'
                ];
            }
        }
        return DB::table('thn_ajaran_mst')
            ->select('id', 'kode_ta', 'kode_kelas', 'periode', 'ket')
            // ->join('ms_koridor','ms_bus.id_koridor','=','ms_koridor.id')
            ->where($data['where'])
            ->paginate(8);
    }

    public function edit($id)
    {
        $data = DB::table('thn_ajaran_mst')
            ->select('id', 'kode_guru', 'nama', 'alamat', 'jenis_kelamin', 'tempat_lahir', 'tgl_lahir', 'agama', 'foto', 'jabatan', 'status', 'ket')
            // ->join('ms_koridor','ms_bus.id_koridor','=','ms_koridor.id')
            ->where('thn_ajaran_mst.id', '=', $id)
            ->first();
        return view('dashboard.transaksi.kelas.edit')->with('data', $data);
    }

    public function get_kelas(Request $request){
        $kode_kelas = $request->kode_kelas;
        return DB::table('master_kelas')
            ->select('id','kelas as text','nama','adm_fee','spp','mc','ket')
            ->where('kode_kelas','=',$kode_kelas)
            ->orderBy('id','asc')
            ->get();
    }

    public function submit(Request $request)
    {
        $type = $request->type;
        $kode_ta = $request->kode_ta;
        $kode_kelas = $request->kelas;
        $tahun_ajaran = $request->tahun_ajaran;
        $kode_siswa = $request->kode_siswa;

        try {
            DB::beginTransaction();
            if ($type == 'baru') {
                $thn_ajaran_mst = new thn_ajaran_mst();
                $thn_ajaran_mst->kode_ta = $kode_ta;
                $thn_ajaran_mst->kode_kelas = $kode_kelas;
                $thn_ajaran_mst->periode = $tahun_ajaran;
                $thn_ajaran_mst->save();

                foreach ($kode_siswa as $s) {
                    $thn_ajaran_trn = new thn_ajaran_trn();
                    $thn_ajaran_trn->kode_ta = $kode_ta;
                    $r = explode("#",$s);
                    $thn_ajaran_trn->kode_siswa = $r[0];
                    $thn_ajaran_trn->ket = $r[1];
                    $thn_ajaran_trn->save();
                }
            } elseif ($type == 'edit') {
                DB::table('thn_ajaran_mst')
                    ->where('id', '=', $request->id)
                    ->update([
                        'kode_ta' => $kode_ta,
                        'kode_kelas' => $kode_kelas,
                        'periode' => $tahun_ajaran,
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
