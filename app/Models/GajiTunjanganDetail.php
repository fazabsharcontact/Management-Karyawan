<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GajiTunjanganDetail extends Model
{
    use HasFactory;

    protected $table = 'gaji_tunjangan_details';
    protected $primaryKey = 'id';

    protected $fillable = [
        'gaji_id',
        'master_tunjangan_id',
        'jumlah',
        'keterangan',
    ];

    public function gaji()
    {
        return $this->belongsTo(Gaji::class, 'gaji_id');
    }

    public function masterTunjangan()
    {
        return $this->belongsTo(MasterTunjangan::class, 'master_tunjangan_id');
    }
}
