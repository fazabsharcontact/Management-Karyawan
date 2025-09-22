<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    protected $table = 'pegawai';
    protected $primaryKey = 'id_pegawai';
    public $timestamps = false;

    protected $fillable = [
        'id_users',
        'nama',
        'email',
        'no_hp',
        'alamat',
        'id_jabatan',
        'tanggal_masuk',
        'gaji',
    ];

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'id_jabatan');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_users');
    }
}