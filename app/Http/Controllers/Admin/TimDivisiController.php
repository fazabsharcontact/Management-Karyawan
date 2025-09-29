<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Divisi;
use Illuminate\Http\Request;

class TimDivisiController extends Controller
{
    /**
     * Menampilkan halaman utama untuk manajemen Tim & Divisi.
     * Mengambil semua divisi beserta tim dan jumlah anggotanya.
     */
    public function index()
    {
        $divisis = Divisi::with(['tims.pegawais'])->withCount('tims')->latest()->get();

        return view('admin.timdivisi.index', compact('divisis'));
    }
}
