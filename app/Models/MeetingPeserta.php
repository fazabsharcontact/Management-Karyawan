<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeetingPeserta extends Model
{
    use HasFactory;

    /**
     * 
     *
     * @var string
     */
    protected $table = 'meeting_pesertas';

    /**
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'meeting_id',
        'pegawai_id',
    ];

    public function meeting()
    {
        return $this->belongsTo(Meeting::class, 'meeting_id');
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id');
    }
}
