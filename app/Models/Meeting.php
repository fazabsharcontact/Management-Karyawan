<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    use HasFactory;

    protected $table = 'meetings';
    protected $primaryKey = 'id';

    protected $fillable = [
        'judul',
        'deskripsi',
        'waktu_mulai',
        'waktu_selesai',
        'lokasi',
        'pembuat_id',
    ];

    public function pembuat()
    {
        return $this->belongsTo(Pegawai::class, 'pembuat_id');
    }

    public function pesertas()
    {
        return $this->belongsToMany(Pegawai::class, 'meeting_pesertas', 'meeting_id', 'pegawai_id');
    }
}
