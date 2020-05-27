<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class sysMenuGroup extends Seeder
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
                'name' => 'System Utility',
                'segment_name' => 'system',
                'icon' => 'fas fa-cogs',
                'ord' => '1'
            ],
            [
                'id' => '2',
                'name' => 'Master Data',
                'segment_name' => 'master',
                'icon' => 'fas fa-database',
                'ord' => '2'
            ],
            [
                'id' => '3',
                'name' => 'Penjualan',
                'segment_name' => 'penjualan',
                'icon' => 'fas fa-bus-alt',
                'ord' => '3'
            ],
            [
                'id' => '4',
                'name' => 'Laporan',
                'segment_name' => 'laporan',
                'icon' => 'fas fa-chart-line',
                'ord' => '4'
            ],
            [
                'id' => '5',
                'name' => 'Problem',
                'segment_name' => 'problem',
                'icon' => 'fas fa-exclamation-triangle',
                'ord' => '5'
            ],
        ];
        foreach ($data as $d) {
            DB::table('sys_menu_groups')
                ->insert($d);
        }
    }
}
