<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class transaksi extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $penumpang = DB::table('ms_penumpang')->select('id','harga')->get()->keyBy('id')->toArray();
        $bus = DB::table('ms_bus')
            ->select('ms_bus.id','id_koridor','trip_a','trip_b')
            ->leftJoin('ms_koridor','ms_bus.id_koridor','=','ms_koridor.id')
            ->get()->keyBy('id')->toArray();
        $shelter = DB::table('master_shelter')->select('id')->get()->keyBy('id')->toArray();
        $pembayaran = DB::table('ms_penumpang_bayar')->select('id','id_pembayaran')->get()->keyBy('id')->toArray();
        $user = DB::table('users')->select('id','username')->get()->toArray();
        $shift = [1,2];
        for ($i=0; $i<10; $i++) {
            $randPenumpang = array_rand($penumpang);
            $randBus = array_rand($bus);
            $randShelter = array_rand($shelter);
            $randPembayaran = array_rand($pembayaran);
            $randUser = array_rand($user);
            $randShift = array_rand($shift);
            $noTransaksi = date('ymd').
                '-'.str_pad($randBus,2,'0',STR_PAD_LEFT).
                '-'.str_pad($user[$randUser]->id,3,'0',STR_PAD_LEFT).
                '-'.str_pad($i,4,'0',STR_PAD_LEFT);

            $trn = new transaksi();
            $trn->no_transaksi = $noTransaksi;
            $trn->tgl_transaksi = date('Y-m-d');
            $trn->jam_transaksi = date('H:i:s',strtotime(mt_rand(0,23).':'.mt_rand(0,59).':'.mt_rand(0,59)));
            $trn->id_penumpang = $randPenumpang;
            $trn->id_koridor = $bus[$randBus]->id_koridor;
            $trn->id_bus = $randBus;
            $trn->id_shelter = $randShelter;
            $trn->trip_a = $bus[$randBus]->trip_a;
            $trn->trip_b = $bus[$randBus]->trip_b;
            $trn->shift = $shift[$randShift];
            $trn->username = $user[$randUser]->username;
            $trn->opsi_bayar = $pembayaran[$randPembayaran]->id_pembayaran;
            $trn->harga = $penumpang[$randPenumpang]->harga;
            $trn->save();
//            DB::table('transaksi')
//                ->insert([
//                    'no_transaksi' => $noTransaksi,
//                    'tgl_transaksi' => date('Y-m-d'),
//                    'jam_transaksi' => date('H:i:s',strtotime(mt_rand(0,23).':'.mt_rand(0,59).':'.mt_rand(0,59))),
//                    'id_penumpang' => $randPenumpang,
//                    'id_koridor' => $bus[$randBus]->id_koridor,
//                    'id_bus' => $randBus,
//                    'id_shelter' => $randShelter,
//                    'trip_a' => $bus[$randBus]->trip_a,
//                    'trip_b' => $bus[$randBus]->trip_b,
//                    'shift' => $shift[$randShift],
//                    'username' => $user[$randUser]->username,
//                    'opsi_bayar' => $pembayaran[$randPembayaran]->id_pembayaran,
//                    'harga' => $penumpang[$randPenumpang]->harga,
//                ]);
        }
    }
}
