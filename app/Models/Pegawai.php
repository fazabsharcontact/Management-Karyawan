<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    use HasFactory;

    protected $table = 'pegawais';
    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'jabatan_id',
        'tim_id',
        'nama',
        'email',
        'no_hp',
        'alamat',
        'tanggal_masuk',
        'gaji_pokok',
        'sisa_cuti_tahunan',
    ];

    // Relasi balik ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi balik ke Jabatan
    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'jabatan_id');
    }

    // Relasi balik ke Tim
    public function tim()
    {
        return $this->belongsTo(Tim::class, 'tim_id');
    }

    // Relasi ke Kehadiran
    public function kehadirans()
    {
        return $this->hasMany(Kehadiran::class, 'pegawai_id');
    }

    // Relasi ke Gaji
    public function gajis()
    {
        return $this->hasMany(Gaji::class, 'pegawai_id');
    }

    // Relasi ke Cuti
    public function cutis()
    {
        return $this->hasMany(Cuti::class, 'pegawai_id');
    }

    public function tugasDiterima()
    {
        return $this->hasMany(Tugas::class, 'penerima_id');
    }
}
