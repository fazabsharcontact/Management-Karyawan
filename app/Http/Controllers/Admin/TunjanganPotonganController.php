<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterTunjangan;
use App\Models\MasterPotongan;
use Illuminate\Http\Request;

class TunjanganPotonganController extends Controller
{
    /**
     * Menampilkan halaman gabungan untuk manajemen
     * jenis tunjangan dan potongan.
     */
    public function index()
    {
        // Ambil semua data dari kedua model dengan paginasi
        // Paginasi diberi nama unik ('tunjangan_page' dan 'potongan_page') agar tidak bentrok
        $masterTunjangans = MasterTunjangan::latest()->paginate(5, ['*'], 'tunjangan_page');
        $masterPotongans = MasterPotongan::latest()->paginate(5, ['*'], 'potongan_page');

        // Kirim kedua data ke satu view yang sama
        return view('admin.tunjangan.index', compact('masterTunjangans', 'masterPotongans'));
    }
}

