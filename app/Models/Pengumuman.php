<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model
{
    use HasFactory;

    protected $table = 'pengumuman';
    protected $primaryKey = 'id';

    protected $fillable = ['user_id', 'judul', 'isi'];

    public function pembuat()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function penerimas()
    {
        return $this->hasMany(PengumumanPenerima::class, 'pengumuman_id');
    }
}
