<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SisaCuti extends Model
{
    use HasFactory;
    protected $table = 'sisa_cutis';
    protected $fillable = ['pegawai_id', 'sisa_cuti'];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id');
    }
}
