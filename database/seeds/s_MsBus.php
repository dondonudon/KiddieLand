<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class s_MsBus extends Seeder
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
                'id_koridor' => '1',
                'nama' => 'BUS I',
                'merk' => 'BUS',
                'no_pol' => 'H 1111 AA',
                'status' => '1',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id_koridor' => '2',
                'nama' => 'BUS II',
                'merk' => 'BUS',
                'no_pol' => 'H 2222 BB',
                'status' => '1',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];
        foreach ($data as $d) {
            DB::table('ms_bus')
                ->insert($d);
        }
    }
}
