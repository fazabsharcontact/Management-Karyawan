<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterPotongan extends Model
{
    use HasFactory;

    protected $table = 'master_potongans';
    protected $primaryKey = 'id';

    protected $fillable = ['nama_potongan', 'deskripsi'];
}
