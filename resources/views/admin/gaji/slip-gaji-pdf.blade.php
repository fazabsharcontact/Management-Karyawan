<!DOCTYPE html>
<html>
<head>
    <title>Slip Gaji</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 12px; color: #333; }
        .container { border: 1px solid #ddd; padding: 20px; width: 100%; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 24px; }
        .header p { margin: 0; font-size: 14px; }
        .info-table { width: 100%; margin-bottom: 20px; }
        .info-table td { padding: 4px 0; }
        .detail-table { width: 100%; border-collapse: collapse; }
        .detail-table th, .detail-table td { border: 1px solid #ddd; padding: 8px; }
        .detail-table th { background-color: #f2f2f2; text-align: left; }
        .footer { margin-top: 30px; }
        .footer td { padding: 5px 0; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>SLIP GAJI KARYAWAN</h1>
            <p>PT. Manajemen Karyawan Sejahtera</p>
        </div>

        <table class="info-table">
            <tr>
                <td width="15%">Nama Pegawai</td>
                <td width="1%">:</td>
                <td>{{ $gaji->pegawai->nama ?? 'N/A' }}</td>
                <td width="15%">Periode Gaji</td>
                <td width="1%">:</td>
                <td>{{ \Carbon\Carbon::create()->month($gaji->bulan)->format('F') }} {{ $gaji->tahun }}</td>
            </tr>
            <tr>
                <td>Jabatan</td>
                <td>:</td>
                <td>{{ $gaji->pegawai->jabatan->nama_jabatan ?? 'N/A' }}</td>
                <td>Tanggal Cetak</td>
                <td>:</td>
                <td>{{ now()->format('d M Y') }}</td>
            </tr>
        </table>

        <table class="detail-table">
            <thead>
                <tr>
                    <th width="50%">Pendapatan</th>
                    <th width="50%">Potongan</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <table>
                            <tr>
                                <td>Gaji Pokok</td>
                                <td class="text-right">Rp {{ number_format($gaji->gaji_pokok, 0, ',', '.') }}</td>
                            </tr>
                            @foreach($gaji->tunjanganDetails as $tunjangan)
                            <tr>
                                <td>{{ $tunjangan->masterTunjangan->nama_tunjangan }}</td>
                                <td class="text-right">Rp {{ number_format($tunjangan->jumlah, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </table>
                    </td>
                    <td>
                        <table>
                            @foreach($gaji->potonganDetails as $potongan)
                            <tr>
                                <td>{{ $potongan->masterPotongan->nama_potongan }}</td>
                                <td class="text-right">Rp {{ number_format($potongan->jumlah, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>

        <table class="footer">
            <tr>
                <td class="font-bold">Total Pendapatan</td>
                <td class="text-right font-bold">Rp {{ number_format($gaji->gaji_pokok + $gaji->total_tunjangan, 0, ',', '.') }}</td>
            </tr>
             <tr>
                <td class="font-bold">Total Potongan</td>
                <td class="text-right font-bold">Rp {{ number_format($gaji->total_potongan, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="font-bold" style="font-size: 14px;">Gaji Bersih (Take Home Pay)</td>
                <td class="text-right font-bold" style="font-size: 14px;">Rp {{ number_format($gaji->gaji_bersih, 0, ',', '.') }}</td>
            </tr>
        </table>

        <div style="margin-top: 40px; text-align: right;">
            <p>Hormat kami,</p>
            <br><br><br>
            <p>(..............................)</p>
            <p><strong>Manajemen HRD</strong></p>
        </div>
    </div>
</body>
</html>