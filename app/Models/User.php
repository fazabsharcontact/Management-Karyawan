<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    // Pakai tabel lama
    protected $table = 'users';

    // Primary key custom
    protected $primaryKey = 'id_users';

    // Kalau bukan auto increment (misal UUID), set false
    public $incrementing = true;

    // Tipe PK
    protected $keyType = 'int';

    protected $fillable = [
        'username',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}