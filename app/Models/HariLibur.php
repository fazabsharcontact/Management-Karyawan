<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HariLibur extends Model
{
    use HasFactory;

    protected $table = 'hari_liburs';
    protected $primaryKey = 'id';

    protected $fillable = ['tanggal', 'keterangan', 'user_id'];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
