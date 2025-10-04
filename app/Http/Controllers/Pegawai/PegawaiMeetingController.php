<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\Meeting;

class PegawaiMeetingController extends Controller
{
    /**
     * Tampilkan daftar meeting.
     */
    public function index()
    {
        // Ambil semua meeting terbaru, beserta pembuat dan jumlah peserta
        $meetings = Meeting::with('pembuat')->withCount('pesertas')->latest()->paginate(10);

        return view('pegawai.meeting.index', compact('meetings'));
    }

    /**
     * Tampilkan detail meeting.
     */
    public function show(Meeting $meeting)
    {
        // Load relasi peserta dan pembuat
        $meeting->load('pembuat', 'pesertas');

        return view('pegawai.meeting.show', compact('meeting'));
    }
}
