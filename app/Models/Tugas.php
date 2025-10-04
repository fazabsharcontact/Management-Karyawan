<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tugas extends Model
{
    use HasFactory;

    protected $table = 'tugas';
    protected $primaryKey = 'id';

    protected $fillable = [
        'judul_tugas',
        'deskripsi',
        'pemberi_id',
        'penerima_id',
        'tenggat_waktu',
        'status',
    ];

    public function pemberi()
    {
        return $this->belongsTo(Pegawai::class, 'pemberi_id');
    }

    public function penerima()
    {
        return $this->belongsTo(Pegawai::class, 'penerima_id');
    }
    public function pengumpulan()
    {
        return $this->hasOne(TugasPengumpulan::class, 'tugas_id');
    }
    public function pengumpulanTerbaru()
    {
        return $this->hasOne(TugasPengumpulan::class, 'tugas_id')->latestOfMany();
    }
}
