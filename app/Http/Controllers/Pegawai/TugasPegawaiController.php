<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\Tugas;
use App\Models\TugasPengumpulan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TugasPegawaiController extends Controller
{
    /**
     * Daftar tugas milik pegawai yang login.
     */
    public function index()
    {
        $pegawai = Auth::user()->pegawai;

        if (! $pegawai) {
            abort(403, 'Anda bukan pegawai.');
        }

        $tugas = Tugas::with('pemberi')
            ->where('penerima_id', $pegawai->id)
            ->orderBy('tenggat_waktu', 'asc')
            ->get();

        return view('pegawai.tugas.index', compact('tugas'));
    }

    /**
     * Detail tugas.
     */
    public function show($id)
    {
        $pegawai = Auth::user()->pegawai;

        if (! $pegawai) {
            abort(403, 'Anda bukan pegawai.');
        }

        $tugas = Tugas::with(['pemberi', 'penerima', 'pengumpulan'])
            ->where('penerima_id', $pegawai->id)
            ->findOrFail($id);

        return view('pegawai.tugas.show', compact('tugas'));
    }

    /**
     * Update status tugas (Baru → Dikerjakan).
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Baru,Dikerjakan',
        ]);

        $pegawai = Auth::user()->pegawai;

        if (! $pegawai) {
            abort(403, 'Anda bukan pegawai.');
        }

        $tugas = Tugas::where('penerima_id', $pegawai->id)->findOrFail($id);

        // cuma boleh pindah dari Baru → Dikerjakan
        if ($tugas->status === 'Baru' && $request->status === 'Dikerjakan') {
            $tugas->status = 'Dikerjakan';
            $tugas->save();
        }

        return redirect()
            ->route('pegawai.tugas.show', $tugas->id)
            ->with('success', 'Status tugas diperbarui.');
    }

    /**
     * Simpan pengumpulan tugas (file upload).
     */
    public function storePengumpulan(Request $request, $id)
    {
        $request->validate([
            'file_tugas' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip',
            'catatan' => 'nullable|string',
        ]);

        $pegawai = Auth::user()->pegawai;

        if (! $pegawai) {
            abort(403, 'Anda bukan pegawai.');
        }

        $tugas = Tugas::where('penerima_id', $pegawai->id)->findOrFail($id);

        // upload file
        $path = $request->file('file_tugas')->store('pengumpulan_tugas', 'public');

        TugasPengumpulan::create([
            'tugas_id'   => $tugas->id,
            'pegawai_id' => $pegawai->id,
            'file_tugas' => $path,
            'catatan'    => $request->catatan,
            'status'     => 'Pending',
        ]);

        // setelah submit → admin yang review, status tugas masih "Dikerjakan"
        return redirect()
            ->route('pegawai.tugas.show', $tugas->id)
            ->with('success', 'Pengumpulan tugas berhasil, menunggu tinjauan admin.');
    }
}
