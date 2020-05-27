<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class s_MsDevice extends Seeder
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
                'nama' => 'DEVICE I',
                'imei' => '99001024535470',
                'kode' => 'D0001',
                'status' => '1',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nama' => 'DEVICE II',
                'imei' => '868029030517106',
                'kode' => 'D0002',
                'status' => '1',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];
        foreach ($data as $d) {
            DB::table('ms_devices')
                ->insert($d);
        }
    }
}
