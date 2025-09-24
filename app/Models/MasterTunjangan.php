<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterTunjangan extends Model
{
    use HasFactory;
    
    protected $table = 'master_tunjangans';
    protected $primaryKey = 'id';

    protected $fillable = ['nama_tunjangan', 'deskripsi'];
}
