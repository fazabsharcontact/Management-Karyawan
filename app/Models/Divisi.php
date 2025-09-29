<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Divisi extends Model
{
    use HasFactory;
    
    protected $table = 'divisis';
    protected $primaryKey = 'id';

    protected $fillable = ['nama_divisi'];

    public function tims()
    {
        return $this->hasMany(Tim::class, 'divisi_id');
    }
}