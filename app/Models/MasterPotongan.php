<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterPotongan extends Model
{
    use HasFactory;

    protected $table = 'master_potongans';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_potongan',
        'deskripsi',
        'jumlah_default', // Kolom baru ditambahkan
    ];

    /**
     * Relasi ke detail gaji untuk bisa menghitung total.
     */
    public function details()
    {
        return $this->hasMany(GajiPotonganDetail::class, 'master_potongan_id');
    }
}
