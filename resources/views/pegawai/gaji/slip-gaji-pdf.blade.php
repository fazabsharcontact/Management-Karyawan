<!DOCTYPE html>
<html>

<head>
    <title>Slip Gaji {{ $gaji->pegawai->nama }}</title>
    <style>
        /* CSS KHUSUS UNTUK PDF (Gunakan CSS Internal atau Inline) */
        body {
            font-family: Arial, sans-serif;
            font-size: 10pt;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .table-gaji,
        .table-gaji th,
        .table-gaji td {
            border: 1px solid #000;
            border-collapse: collapse;
            padding: 5px;
        }

        .table-gaji {
            width: 100%;
            margin-top: 15px;
        }
    </style>
</head>

<body>

    <div class="header">
        <h2>SLIP GAJI PEGAWAI</h2>
        <p>Periode: {{ $gaji->bulan }} / {{ $gaji->tahun }}</p>
    </div>

    <p>Nama: {{ $gaji->pegawai->nama }}</p>
    <p>Jabatan: {{ $gaji->pegawai->jabatan->nama_jabatan ?? '-' }}</p>

    <table class="table-gaji">
        <thead>
            <tr>
                <th colspan="2" style="width: 50%;">PENERIMAAN</th>
                <th colspan="2" style="width: 50%;">POTONGAN</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Gaji Pokok</td>
                <td style="text-align: right;">{{ number_format($gaji->gaji_pokok) }}</td>
                <td colspan="2" style="background-color: #f0f0f0;">&nbsp;</td>
            </tr>
            @foreach ($gaji->tunjanganDetails as $t)
                <tr>
                    <td>{{ $t->masterTunjangan->nama_tunjangan }}</td>
                    <td style="text-align: right;">{{ number_format($t->jumlah) }}</td>
                    <td colspan="2" style="background-color: #f0f0f0;">&nbsp;</td>
                </tr>
            @endforeach

            @foreach ($gaji->potonganDetails as $p)
                <tr>
                    <td colspan="2" style="background-color: #f0f0f0;">&nbsp;</td>
                    <td>{{ $p->masterPotongan->nama_potongan }}</td>
                    <td style="text-align: right;">{{ number_format($p->jumlah) }}</td>
                </tr>
            @endforeach

            <tr>
                <td style="font-weight: bold;">Total Tunjangan</td>
                <td style="text-align: right; font-weight: bold;">{{ number_format($gaji->total_tunjangan) }}</td>
                <td style="font-weight: bold;">Total Potongan</td>
                <td style="text-align: right; font-weight: bold;">{{ number_format($gaji->total_potongan) }}</td>
            </tr>
        </tbody>
    </table>

    <h3 style="margin-top: 20px; text-align: right; border-top: 1px solid #000; padding-top: 10px;">
        GAJI BERSIH: Rp {{ number_format($gaji->gaji_bersih) }}
    </h3>

</body>

</html>
