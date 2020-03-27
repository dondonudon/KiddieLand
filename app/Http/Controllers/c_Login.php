<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class c_Login extends Controller
{
    public function index() {
        if (session()->has('status')) {
            if (session()->get('status') == 'logged in') {
                return redirect('/');
            } else {
                return view('dashboard.login');
            }
        } else {
            return view('dashboard.login');
        }
    }

    public function submit(Request $request) {
        $username = $request->username;
        $password = $request->password;

        try {
            $dtUser = DB::table('users')
                ->where('username','=',$username);
            if ($dtUser->exists()) {
                $user = $dtUser->first();
                if (Crypt::decryptString($user->password) == $password) {
                    $request->session()->put([
                        'status' => 'logged in',
                        'username' => $username,
                        'name' => $user->name,
                        'user_id' => $user->id,
                        'created_at' => $user->created_at
                    ]);
                    $result = 'success';
                } else {
                    $result = 'Password salah.';
                }
            } else {
                $result = 'Username tidak terdaftar.';
            }
            return $result;
        } catch (\Exception $ex) {
            return response()->json($ex);
        }
    }

    public function logout(Request $request) {
        try {
            $request->session()->flush();
            return 'success';
        } catch (\Exception $ex) {
            return response()->json($ex);
        }
    }

    public function resetPassword() {
        if (Session::exists('username')) {
            return view('dashboard.reset-password');
        } else {
            return redirect('/');
        }
    }

    public function resetPasswordSubmit(Request $request) {
        $username = Session::get('username');
        $lama = $request->password_lama;
        $baru = $request->password_baru;

        try {
            DB::beginTransaction();
            $dtUser = DB::table('users')->where('username','=',$username)->first();
            if (Crypt::decryptString($dtUser->password) == $lama) {
                DB::table('users')->where('username','=',$username)
                    ->update([
                        'password' => Crypt::encryptString($baru)
                    ]);
                $result = 'success';
            } else {
                $result = 'password salah';
            }
            DB::commit();
            Session::flush();
            return $result;
        } catch (\Exception $ex) {
            $err = [$ex];
            return response()->json($err);
        }
    }
}
