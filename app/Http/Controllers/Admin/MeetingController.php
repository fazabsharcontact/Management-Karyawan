<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Meeting;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MeetingController extends Controller
{
    /**
     */
    public function index()
    {
        $meetings = Meeting::with('pembuat')->withCount('pesertas')->latest()->paginate(10);
        return view('admin.meeting.index', compact('meetings'));
    }

    /**
     */
    public function create()
    {
        $pegawais = Pegawai::orderBy('nama')->get();
        return view('admin.meeting.create', compact('pegawais'));
    }

    /**
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'waktu_mulai' => 'required|date',
            'waktu_selesai' => 'required|date|after_or_equal:waktu_mulai',
            'lokasi' => 'required|string|max:255',
            'pembuat_id' => 'required|exists:pegawais,id',
            'peserta_ids' => 'required|array',
            'peserta_ids.*' => 'exists:pegawais,id',
        ]);
        
        DB::transaction(function () use ($validated) {
            $meetingData = [
                'judul' => $validated['judul'],
                'deskripsi' => $validated['deskripsi'],
                'waktu_mulai' => $validated['waktu_mulai'],
                'waktu_selesai' => $validated['waktu_selesai'],
                'lokasi' => $validated['lokasi'],
                'pembuat_id' => $validated['pembuat_id'],
            ];

            $meeting = Meeting::create($meetingData);
            $meeting->pesertas()->sync($validated['peserta_ids']);
        });

        return redirect()->route('admin.meeting.index')
            ->with('success', 'Meeting baru berhasil dijadwalkan.');
    }

    /**
     */
    public function edit(Meeting $meeting)
    {
        $pegawais = Pegawai::orderBy('nama')->get();
        $pesertaIds = $meeting->pesertas->pluck('id')->toArray();

        return view('admin.meeting.edit', compact('meeting', 'pegawais', 'pesertaIds'));
    }

    /**
     */
    public function update(Request $request, Meeting $meeting)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'waktu_mulai' => 'required|date',
            'waktu_selesai' => 'required|date|after_or_equal:waktu_mulai',
            'lokasi' => 'required|string|max:255',
            'pembuat_id' => 'required|exists:pegawais,id',
            'peserta_ids' => 'required|array',
            'peserta_ids.*' => 'exists:pegawais,id',
        ]);

        DB::transaction(function () use ($validated, $meeting) {
            $meetingData = [
                'judul' => $validated['judul'],
                'deskripsi' => $validated['deskripsi'],
                'waktu_mulai' => $validated['waktu_mulai'],
                'waktu_selesai' => $validated['waktu_selesai'],
                'lokasi' => $validated['lokasi'],
                'pembuat_id' => $validated['pembuat_id'],
            ];
            
            $meeting->update($meetingData);
            $meeting->pesertas()->sync($validated['peserta_ids']);
        });

        return redirect()->route('admin.meeting.index')
            ->with('success', 'Data meeting berhasil diperbarui.');
    }

    /**
     */
    public function destroy(Meeting $meeting)
    {
        $meeting->delete();
        return redirect()->route('admin.meeting.index')
            ->with('success', 'Meeting berhasil dihapus.');
    }
}

