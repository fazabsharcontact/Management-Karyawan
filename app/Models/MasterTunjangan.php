<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterTunjangan extends Model
{
    use HasFactory;
    
    protected $table = 'master_tunjangans';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_tunjangan',
        'deskripsi',
        'jumlah_default', // Kolom baru ditambahkan
    ];

    /**
     * Relasi ke detail gaji untuk bisa menghitung total.
     */
    public function details()
    {
        return $this->hasMany(GajiTunjanganDetail::class, 'master_tunjangan_id');
    }
}