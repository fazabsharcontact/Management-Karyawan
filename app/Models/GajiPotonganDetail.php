<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GajiPotonganDetail extends Model
{
    use HasFactory;

    protected $table = 'gaji_potongan_details';
    protected $primaryKey = 'id';

    protected $fillable = [
        'gaji_id',
        'master_potongan_id',
        'jumlah',
        'keterangan',
    ];

    public function gaji()
    {
        return $this->belongsTo(Gaji::class, 'gaji_id');
    }

    public function masterPotongan()
    {
        return $this->belongsTo(MasterPotongan::class, 'master_potongan_id');
    }
}
