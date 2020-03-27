<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class s_MsKoridor extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'id' => '1',
                'koridor' => 'Koridor I',
                'rute' => 'Semarang - Karangawen',
                'trip_a' => 'Semarang',
                'trip_b' => 'Karangawen',
                'longitude_a' => '110.42347404159905',
                'latitude_a' => '-6.989239886915921',
                'longitude_b' => '110.57758641371518',
                'latitude_b' => '-7.044446475306002',
                'status' => '1',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => '2',
                'koridor' => 'Koridor II',
                'rute' => 'Semarang - Salatiga',
                'trip_a' => 'Semarang',
                'trip_b' => 'Salatiga',
                'longitude_a' => '110.42347404159905',
                'latitude_a' => '-6.989239886915921',
                'longitude_b' => '110.57758641371518',
                'latitude_b' => '-7.044446475306002',
                'status' => '1',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];
        foreach ($data as $d) {
            DB::table('ms_koridor')
                ->insert($d);
        }
    }
}
