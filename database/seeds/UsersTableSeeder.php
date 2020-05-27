<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new \App\User();
        $user->id = 1;
        $user->name = 'Developer';
        $user->username = 'dev';
        $user->email = 'laurentiuskevin44@gmail.com';
        $user->password = \Illuminate\Support\Facades\Crypt::encryptString('dev');
        $user->no_hp = '081901115314';
        $user->system = 0;
        $user->save();
    }
}
