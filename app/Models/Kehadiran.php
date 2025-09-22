<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// app/Models/Kehadiran.php
class Kehadiran extends Model
{
    protected $table = 'kehadiran'; // sesuai nama tabel di DB
    protected $primaryKey = 'id_kehadiran'; // kalau pakai primary key custom
}

// app/Models/Gaji.php
class Gaji extends Model
{
    protected $table = 'gaji';
    protected $primaryKey = 'id_gaji';
}