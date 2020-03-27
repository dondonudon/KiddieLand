<?php

namespace App\Http\Controllers;

use App\msKoridor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class c_Overview extends Controller
{
    public function dataset() {
        try {
            $tgl = date('Y-m-d');
//            $tgl = '2019-12-27';
            return DB::table('transaksi')
                ->select(
                    'ms_penumpang.jenis',
                    DB::raw('COUNT(transaksi.id) as total')
                )
                ->leftJoin('ms_penumpang','transaksi.id_penumpang','=','ms_penumpang.id')
                ->where('tgl_transaksi','=',$tgl)
                ->groupBy('ms_penumpang.jenis')
                ->get();
        } catch (\Exception $ex) {
            return response()->json([$ex]);
        }
    }

    public function index() {
        $data = $this->dataset();
        return view('dashboard.overview.index')->with('transaksi',$data);
    }

    public function statistics(Request $request) {
        $day = $request->day;
        try {
            $last7dayTicket = [];
            $last7dayHarga = [];
            foreach ($day as $d) {
                $total = DB::table('transaksi')
                    ->select(DB::raw('COUNT(no_transaksi) as total,SUM(harga) as total_harga'))
                    ->where('tgl_transaksi','=',date('Y-m-d',strtotime($d)))->first();
                $last7dayTicket[] = $total->total ?? 0;
                $last7dayHarga[] = $total->total_harga ?? 0;
            }
            $userAplikasi = DB::table('users')
                ->selectRaw('COUNT(id) AS total')
                ->whereIn('system',[0,1])->first();
            $koridor = DB::table('ms_koridor')
                ->selectRaw('COUNT(id) AS total')
                ->where('status','=',1)->first();
            $bus = DB::table('ms_bus')
                ->selectRaw('COUNT(id) AS total')
                ->where('status','=',1)->first();
            $shelter = DB::table('master_shelter')
                ->selectRaw('COUNT(id) AS total')
                ->where('status','=',1)->first();
            $problem = DB::table('problems')
                ->selectRaw('COUNT(id) AS total')
                ->where('status','=',0)->first();
            $transaksiBulanIni = DB::table('transaksi')
                ->select(DB::raw('SUM(harga) as transaksi'))
                ->whereBetween('tgl_transaksi',[
                    date('Y-m-d',strtotime('first day of this month')),
                    date('Y-m-d',strtotime('last day of this month'))
                ])->first();
            $result = [
                'user_aplikasi' => $userAplikasi->total,
                'koridor' => $koridor->total,
                'bus' => $bus->total,
                'shelter' => $shelter->total,
                'problem' => $problem->total,
                'statistics_chart' => $last7dayHarga,
                'tiket_hari_ini' => $last7dayTicket[6],
                'transaksi_hari_ini' => $last7dayHarga[6] ?? 0,
                'transaksi_bulan_ini' => $transaksiBulanIni->transaksi,
            ];
            return response()->json($result);
        } catch (\Exception $ex) {
            return response()->json([$ex,$day]);
        }
    }

    public function koridorLocation() {
        $location = [];
        try {
            $Koridor = DB::table('ms_koridor')->get();
            foreach ($Koridor as $k) {
                $location[] = [
                    'lat_a' => $k->latitude_a,
                    'lng_a' => $k->longitude_a,
                    'lat_b' => $k->latitude_b,
                    'lng_b' => $k->longitude_b,
                    'shelter' => DB::table('master_shelter')
                        ->select(
                            'latitude as lat','longitude as lng',
                            DB::raw('CONCAT("true") as stopover')
                            )
                        ->where('id_koridor','=',$k->id)
                        ->get()->toArray()
                ];
            }
            return $location;
        } catch (\Exception $ex) {
            return response()->json([$ex]);
        }
    }
}
