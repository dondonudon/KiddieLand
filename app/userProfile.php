<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class userProfile extends Model
{
    protected $table = 'user_profile';
    protected $fillable = [
        'username',
        'foto',
        'no_sk',
        'no_spk',
        'tempat_lahir',
        'tgl_lahir',
        'jenis_kelamin',
        'alamat',
        'jabatan',
        'agama',
        'no_ktp',
        'tingkat_pendidikan',
        'jurusan',
        'keterangan',
    ];
}
