<?php

namespace App\Http\Controllers;

use App\muatanBus;
use App\problem;
use App\transaksi;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use function GuzzleHttp\Psr7\str;

class c_Android extends Controller
{
    /*
     * Login User
     */
    public function login(Request $request) {
        $username = $request->username;
        $password = $request->password;
        $imei = $request->imei;
        try {
            $result = [];
            $device = DB::table('ms_devices')->where('imei','=',$imei);
            $dbUser = DB::table('users')
                ->where([
                    ['username','=',$username],
                    ['status','=','1'],
                ])
                ->whereIn('system',['0','1']);
            if ($dbUser->exists() && $device->exists()) {
                $dtUser = $dbUser->first();
                if (Crypt::decryptString($dtUser->password) == $password) {
                    $result = [
                        'status' => 'success',
                        'data' => DB::table('users')
                            ->select('id','username','name','email','no_hp','created_at')
                           
                            ->where('username','=',$username)->first(),
                        'device' => $device->first(),
                        'penumpang' => $this->penumpangTidakLibur(),
                    ];
                } else {
                    $result = [
                        'status' => 'failed',
                        'message' => 'password salah'
                    ];
                }
            } else {
                $result = [
                    'status' => 'failed',
                    'message' => 'user tidak terdaftar'
                ];
            }
            return response()->json($result);
        } catch (\Exception $ex) {
            return response()->json($ex);
        }
    }

    public function syncMaster() {
        try {
            return [
                'koridor' => $this->datasetKoridor(),
                'shelter' => $this->datasetShelter(),
                'bus' => $this->datasetBus(),
                'pembayaran' => $this->datasetPembayaran(),
                'penumpang' => $this->datasetPenumpang(),
                'penumpang_bayar' => $this->datasetPenumpangBayar(),
            ];
        } catch (\Exception $ex) {
            return response()->json([$ex]);
        }
    }

    public function datasetKoridor() {
        return DB::table('ms_koridor')
            ->select('id','koridor as koridor','rute','trip_a','trip_b')
            ->where('status','=','1')
            ->orderBy('koridor','asc')
            ->limit(10)
            ->get();
    }

    public function datasetShelter() {
        return DB::table('master_shelter')
            ->select('master_shelter.id','master_shelter.id_koridor','ms_koridor.koridor','master_shelter.nama as shelter','master_shelter.lokasi','master_shelter.longitude','master_shelter.latitude')
            ->leftJoin('ms_koridor','master_shelter.id_koridor','=','ms_koridor.id')
            ->where('master_shelter.status','=','1')
            ->orderBy('master_shelter.nama','asc')
            ->get();
    }

    public function datasetBus() {
        return DB::table('ms_bus')
            ->select('id','nama as kode','merk','no_pol','id_koridor')
            ->where('status','=','1')
            ->orderBy('nama','asc')
            ->get();
    }

    public function datasetPenumpang() {
        return DB::table('ms_penumpang')
            ->select(
                'id',
                'jenis',
                'harga'
            )
            ->where('status','=','1')
            ->orderBy('id','asc')
            ->get();
    }

    public function datasetPembayaran() {
        return DB::table('ms_pembayaran')
            ->select(
                'id','nama','keterangan'
            )
            ->where('status','=','1')
            ->orderBy('nama','asc')
            ->get();
    }

    public function datasetPenumpangBayar() {
        return DB::table('ms_penumpang_bayar')
            ->select(
                'id','id_penumpang','id_pembayaran'
            )
            ->orderBy('id_penumpang','asc')
            ->get();
    }

    public function penumpangTidakLibur() {
        $date = date('Y-m-d');
        $libur = DB::table('ms_libur')->where('tanggal','=',$date)->pluck('id_penumpang');
        $libur[] = 99;
        return DB::table('ms_penumpang')
            ->select('id','jenis as text','harga')
//            ->whereNotIn('id',$libur)
            ->where('status','=','1')
            ->orderBy('id','asc')
        
            ->get();
    }

    // insertPenumpang turun
    public function insertPenumpangTurun(Request $request) {
        try {
            DB::beginTransaction(); 
            $data = json_decode($request->data);
   
            foreach ($data as $d) {        
                $lmb = new muatanBus();
                $lmb->id_koridor = $d->_idKoridor;
                $lmb->idPenumpang = '88';
                $lmb->id_shelter = $d->_idShelter;
                $lmb->id_bus = $d->_idbus;
                $lmb->username = $d->_username;
                $lmb->shift = $d->_shift;
                $lmb->tanggal = $d->_tgl;
                $lmb->tipe = 2;
                $lmb->arah = 2;
                $lmb->total = $d->_total;
                $lmb->save();
            
            }

            DB::commit();
            $result = [
                'status' => 'success'
            ];
            
            return response()->json($result);
        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->json([$ex]);
   
//            dd('exception block',$ex);
        }
    }

    public function sync(Request $request) {
        try {
            $data = json_decode($request->data);
            DB::beginTransaction();
            foreach ($data as $d) {
                $trip = explode('-',$d->trip);
                $trn = new transaksi();
                $trn->no_transaksi = $d->no_trn;
                $trn->tgl_transaksi = $d->tgl;
                $trn->jam_transaksi = date('H:i:s',strtotime($d->jam));
                $trn->id_penumpang = $d->penumpang;
                $trn->id_koridor = $d->koridor;
                $trn->id_bus = $d->bus;
                $trn->id_shelter = $d->shelter;
                $trn->trip_a = $trip[0];
                $trn->trip_b = $trip[1];
                $trn->shift = $d->shift;
                $trn->username = $d->username;
                $trn->opsi_bayar = $d->opsi_bayar;
                $trn->harga = $d->harga;
                $trn->save();

                $lmb = new muatanBus();
                $lmb->id_koridor = $d->koridor;
                $lmb->idPenumpang = $d->penumpang;
                $lmb->id_shelter = $d->shelter;
                $lmb->id_bus = $d->bus;
                $lmb->username = $d->username;
                $lmb->shift = $d->shift;
                $lmb->tanggal = $d->tgl;
                $lmb->tipe = 2;
                $lmb->arah = 1;
                $lmb->total = 1;
                $lmb->save();
            }
            DB::commit();
            $result = [
                'status' => 'success'
            ];
            return response()->json($result);
        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->json([$ex]);
//            dd('exception block',$ex);
        }
    }

    /*
     * Problem Report
     */
    public function problemReport(Request $request) {
        $koridor = $request->koridor;
        $bus = $request->bus;
        $ket = $request->keterangan;
        $user = $request->username;
        $lng = $request->longitude ?? '';
        $lat = $request->latitude ?? '';
        $trip = $request->trip;
        $shift = $request->shift;
        try {
            DB::beginTransaction();
            $report = new problem();
            $report->id_koridor = $koridor;
            $report->id_bus = $bus;
            $report->keterangan = $ket;
            $report->username = $user;
            $report->longitude = $lng;
            $report->latitude = $lat;
            $report->trip = $trip;
            $report->shift = $shift;
            $report->save();
            DB::commit();
            return response()->json(['status'=>'success']);
        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->json(['status'=>'failed','error'=>$ex]);
        }
    }

    public function rekapTransaksi(Request $request) {
        $username = $request->username;
        $tgl = $request->tgl;
        try {
            return DB::table('transaksi')
                ->select(
                    'transaksi.id','transaksi.no_transaksi','transaksi.tgl_transaksi','transaksi.jam_transaksi',
                    DB::raw('FORMAT(transaksi.harga,0) as harga'),
                    'ms_penumpang.jenis as penumpang',
                    DB::raw('COALESCE(ms_koridor.koridor, "-") as koridor'),
                    'ms_bus.nama as bus',
                    DB::raw('COALESCE(master_shelter.nama, "-") as shelter'),
                    'ms_pembayaran.nama as opsi_bayar',
                    'users.name as nama_petugas','users.username','transaksi.created_at as tgl_sync'
                )
                ->leftJoin('ms_penumpang','transaksi.id_penumpang','=','ms_penumpang.id')
                ->leftJoin('ms_bus','transaksi.id_bus','=','ms_bus.id')
                ->leftJoin('ms_koridor','transaksi.id_koridor','=','ms_koridor.id')
                ->leftJoin('master_shelter','transaksi.id_shelter','=','master_shelter.id')
                ->leftJoin('ms_pembayaran','transaksi.opsi_bayar','=','ms_pembayaran.id')
                ->leftJoin('users','transaksi.username','=','users.username')
                ->where([
                    ['transaksi.tgl_transaksi','=',date('Y-m-d',strtotime($tgl))],
                    ['transaksi.username','=',$username]
                ])
                ->get();
        } catch (\Exception $ex) {
            return response()->json(['status'=>'failed','message'=>$ex]);
        }
    }

    public function lmbReport(Request $request) {
        $tgl = date('Y-m-d',strtotime($request->tgl));
        $koridor = $request->koridor;
        $username = $request->username;
        $idShelter;
        $data = [];
        try {
            $shelters = DB::table('master_shelter')
                ->where([
                    ['id_koridor','=',$koridor],
                    ['status','=',1],
                ])
                ->get();

                
            foreach ($shelters as $shelter) {
                $dt = new \stdClass();
                $dt->id = $shelter->id;
                $dt->nama = $shelter->nama;
                $dt->naik = 0;
                $dt->turun = 0;
                $idShelter= $shelter->id;
//                $dt->total = 0;
$array=[]; 
$dt->penumpang=$array;
                $msKoridor = DB::table('muatan_bus')
                
                    ->where([
                        ['tanggal','=',$tgl],
                        ['id_shelter','=',$shelter->id],
                        ['id_koridor','=',$koridor],
                        ['username','=',$username],
                        ['tipe','=','2'],
                    ])
                    ->orderBy('idPenumpang', 'ASC')
                    ->get();
      
                foreach ($msKoridor as $k) {
                    if ($k->arah == 1) {
                        $dt->naik += $k->total;
          if($k->id_shelter==$idShelter)
          {
            $array[]= $k;
            $dt->penumpang=$array;

          }

         
//                        $dt->total += $k->total;
                    } else {
                        $dt->turun += $k->total;
//                        $dt->total -= $k->total;
                    }
                }
            
            
                $data[] = $dt;
      
            }
       
            return $data;
        } catch (\Exception $ex) {
            return \response()->json([$ex,$tgl]);
//            dd('Exception Block',$ex);
        }
    }
}
