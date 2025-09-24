<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gaji extends Model
{
    use HasFactory;

    protected $table = 'gajis';
    protected $primaryKey = 'id';

    protected $fillable = [
        'pegawai_id',
        'bulan',
        'tahun',
        'gaji_pokok',
        'total_tunjangan',
        'total_potongan',
        'gaji_bersih',
    ];

    // Relasi balik ke Pegawai
    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id');
    }

    // Relasi ke detail tunjangan
    public function tunjanganDetails()
    {
        return $this->hasMany(GajiTunjanganDetail::class, 'gaji_id');
    }

    // Relasi ke detail potongan
    public function potonganDetails()
    {
        return $this->hasMany(GajiPotonganDetail::class, 'gaji_id');
    }
}
