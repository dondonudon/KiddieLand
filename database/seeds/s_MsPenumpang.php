<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class s_MsPenumpang extends Seeder
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
                'jenis' => 'Umum',
                'harga' => '4000',
                'status' => '1',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => '2',
                'jenis' => 'Veteran',
                'harga' => '2000',
                'status' => '1',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => '3',
                'jenis' => 'Pelajar',
                'harga' => '2000',
                'status' => '1',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => '4',
                'jenis' => 'Buruh',
                'harga' => '4000',
                'status' => '1',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => '99',
                'jenis' => 'Transit',
                'harga' => '0',
                'status' => '1',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]
        ];
        foreach ($data as $d) {
            DB::table('ms_penumpang')
                ->insert($d);
        }
    }
}
