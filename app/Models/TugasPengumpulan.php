<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TugasPengumpulan extends Model
{
    use HasFactory;

    protected $table = 'tugas_pengumpulan';

    protected $fillable = [
        'tugas_id',
        'pegawai_id',
        'file',
        'catatan',
        'status',
    ];

    public function tugas()
    {
        return $this->belongsTo(Tugas::class, 'tugas_id');
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id');
    }
}
