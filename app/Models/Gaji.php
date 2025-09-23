<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gaji extends Model
{
    protected $table = 'gaji';
    protected $primaryKey = 'id_gaji';
    public $timestamps = false;

    protected $fillable = [
        'id_pegawai',
        'bulan',
        'tahun',
        'total_gaji',
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'id_pegawai');
    }
}