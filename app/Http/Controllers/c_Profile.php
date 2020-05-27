<?php

namespace App\Http\Controllers;

use App\userProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class c_Profile extends Controller
{
    public function edit() {
        $username = request()->session()->get('username');

        $data['user'] = DB::table('users')->where('username','=',$username)->first();
        $data['profile'] = DB::table('user_profile')->where('username','=',$username)->first();
        return view('dashboard.profile.edit')->with('data',$data);
    }

    public function submit(Request $request) {
        $username = $request->username;
        $name = $request->name;
        $email = $request->email;
        $noHp = $request->no_hp;
        $foto = ($request->foto !== '') ? $request->file('foto') : '';
        $noSk = ($request->no_sk !== '') ? $request->no_sk : '';
        $noSpk = ($request->no_spk !== '') ? $request->no_spk : '';
        $tempatLahir = ($request->tempat_lahir !== '') ? $request->tempat_lahir : '';
        $tglLahir = ($request->tgl_lahir !== '') ? $request->tgl_lahir : '';
        $jenisKelamin = ($request->jenis_kelamin !== '') ? $request->jenis_kelamin : '';
        $alamat = ($request->alamat !== '') ? $request->alamat : '';
        $jabatan = ($request->jabatan !== '') ? $request->jabatan : '';
        $agama = ($request->agama !== '') ? $request->agama : '';
        $noKtp = ($request->no_ktp !== '') ? $request->no_ktp : '';
        $tingkatPendidikan = ($request->tingkat_pendidikan !== '') ? $request->tingkat_pendidikan : '';
        $jurusan = ($request->jurusan !== '') ? $request->jurusan : '';
        $keterangan = ($request->keterangan !== '') ? $request->keterangan : '';

        try {
            DB::beginTransaction();

            $filename = null;

            if ($foto !== '') {
                $filename = $username.'.'.$foto->getClientOriginalExtension();
                Storage::putFileAs('public',$foto,$filename);
            }

            DB::table('users')
                ->where('username','=',$username)
                ->update([
                    'name' => $name,
                    'email' => $email,
                    'no_hp' => $noHp,
                ]);

            userProfile::updateOrCreate(
                ['username' => $username],
                [
                    'username' => $username,
                    'foto' => $filename,
                    'no_sk' => $noSk,
                    'no_spk' => $noSpk,
                    'tempat_lahir' => $tempatLahir,
                    'tgl_lahir' => $tglLahir,
                    'jenis_kelamin' => $jenisKelamin,
                    'alamat' => $alamat,
                    'jabatan' => $jabatan,
                    'agama' => $agama,
                    'no_ktp' => $noKtp,
                    'tingkat_pendidikan' => $tingkatPendidikan,
                    'jurusan' => $jurusan,
                    'keterangan' => $keterangan,
                ]
            );

            DB::commit();
            return 'success';
        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->json([$ex,$username]);
        }
    }

    public function resetPassword() {
        $username = \request()->session()->get('username');
        $data = DB::table('users')
            ->where('username','=',$username)
            ->first();
        return view('reset-password')->with('data',$data);
    }

    public function resetPasswordSubmit(Request $request) {
        $username = $request->session()->get('username');
        $oldPass = $request->password_lama;
        $newPass = $request->password_baru;

        try {
            DB::beginTransaction();

            $dbUser = DB::table('users')
                ->where('username','=',$username)
                ->first();
            if (Crypt::decryptString($dbUser->password) == $oldPass) {
                DB::table('users')
                    ->where('username','=',$username)
                    ->update([
                        'password' => Crypt::encryptString($newPass)
                    ]);
                $result = 'success';
                $request->session()->flush();
            } else {
                $result = 'password salah';
            }

            DB::commit();
            return $result;
        } catch (\Exception $ex) {
            return response()->json($ex);
        }
    }
}
