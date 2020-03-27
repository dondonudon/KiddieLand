<?php

namespace App\Http\Controllers;

use App\master_thn_ajaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class c_MasterTA extends Controller
{
    public function index()
    {
        return view('dashboard.master-data.thn-ajaran.baru');
    }

    public function list()
    {
        return view('dashboard.master-data.thn-ajaran.list');
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
        return DB::table('master_thn_ajaran')
            ->select('id', 'periode', 'bulan', 'ket')
            // ->join('ms_koridor','ms_bus.id_koridor','=','ms_koridor.id')
            ->where($data['where'])
            ->paginate(8);
    }

    public function edit($id)
    {
        $data = DB::table('master_thn_ajaran')
            ->select('id', 'periode', 'bulan', 'ket')
            // ->join('ms_koridor','ms_bus.id_koridor','=','ms_koridor.id')
            ->where('master_thn_ajaran.id', '=', $id)
            ->first();
        return view('dashboard.master-data.thn-ajaran.edit')->with('data', $data);
    }

    public function submit(Request $request)
    {
        $type = $request->type;
        $periode = $request->periode;
        $bulan = $request->bulan;
        $ket = $request->ket;

        try {
            DB::beginTransaction();
            if ($type == 'baru') {
                $master_thn_ajaran = new master_thn_ajaran();
                $master_thn_ajaran->periode = $periode;
                $master_thn_ajaran->bulan = $bulan;
                $master_thn_ajaran->ket = $ket;
                $master_thn_ajaran->save();
            } elseif ($type == 'edit') {
                DB::table('master_thn_ajaran')
                    ->where('id', '=', $request->id)
                    ->update([
                        'periode' => $periode,
                        'bulan' => $bulan,
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
