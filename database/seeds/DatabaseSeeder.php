<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(sysMenuGroup::class);
        $this->call(sysMenu::class);
        $this->call(s_MsKoridor::class);
        $this->call(s_MsBus::class);
        $this->call(s_MsPenumpang::class);
        $this->call(s_MsHariLibur::class);
        $this->call(s_MsDevice::class);
    }
}
