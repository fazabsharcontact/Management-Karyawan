<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tim extends Model
{
    use HasFactory;

    protected $table = 'tims';
    protected $primaryKey = 'id';

    protected $fillable = ['divisi_id', 'nama_tim'];

    public function divisi()
    {
        return $this->belongsTo(Divisi::class, 'divisi_id');
    }

    public function pegawais()
    {
        return $this->hasMany(Pegawai::class, 'tim_id');
    }
}
