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
        $masterTunjangans = MasterTunjangan::latest()->paginate(5, ['*'], 'tunjangan_page');
        $masterPotongans = MasterPotongan::latest()->paginate(5, ['*'], 'potongan_page');
        return view('admin.tunjangan.index', compact('masterTunjangans', 'masterPotongans'));
    }
}