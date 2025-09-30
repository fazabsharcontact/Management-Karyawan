<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\CarbonPeriod;

class Cuti extends Model
{
    use HasFactory;
    protected $table = 'cutis';
    protected $fillable = [
        'pegawai_id', 'tanggal_mulai', 'tanggal_selesai',
        'keterangan', 'status', 'disetujui_oleh_id',
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id');
    }
    public function disetujuiOleh()
    {
        return $this->belongsTo(User::class, 'disetujui_oleh_id');
    }

    /**
     * Accessor untuk menghitung durasi cuti dalam HARI KERJA.
     */
    public function getDurasiHariKerjaAttribute(): int
    {
        if (!$this->tanggal_mulai || !$this->tanggal_selesai) return 0;
        $period = CarbonPeriod::create($this->tanggal_mulai, $this->tanggal_selesai);
        $weekdays = 0;
        foreach ($period as $date) {
            if (!$date->isWeekend()) {
                $weekdays++;
            }
        }
        return $weekdays;
    }
}