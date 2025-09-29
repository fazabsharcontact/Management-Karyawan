<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengumumanPenerima extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terhubung dengan model ini.
     *
     * @var string
     */
    protected $table = 'pengumuman_penerimas';

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'pengumuman_id',
        'target_type',
        'target_id',
    ];

    /**
     * Mendefinisikan relasi "belongsTo" ke model Pengumuman.
     * Setiap record penerima terhubung ke satu pengumuman.
     */
    public function pengumuman()
    {
        return $this->belongsTo(Pengumuman::class, 'pengumuman_id');
    }
}
