<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Slip Gaji - {{ $gaji->pegawai->nama }} - {{ \Carbon\Carbon::create()->month($gaji->bulan)->format('F') }} {{ $gaji->tahun }}</title>
    <style>
        body {
            font-family: 'Segoe UI', 'Helvetica', 'Arial', sans-serif;
            font-size: 10px; /* Dikurangi dari 12px untuk lebih compact */
            color: #2d3748;
            line-height: 1.4; /* Dikurangi dari 1.5 untuk menghemat ruang vertikal */
            background-color: #f7fafc;
            margin: 0;
            padding: 10px; /* Dikurangi dari 20px */
        }
        .container {
            max-width: 650px; /* Dikurangi dari 800px untuk layout lebih sempit */
            margin: 0 auto;
            background: white;
            padding: 20px; /* Dikurangi dari 40px */
            border-radius: 8px; /* Dikurangi dari 12px */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05); /* Shadow lebih subtle */
            border: 1px solid #e2e8f0;
        }
        .header {
            text-align: center;
            margin-bottom: 15px; /* Dikurangi dari 30px */
            padding-bottom: 10px; /* Dikurangi dari 20px */
            border-bottom: 2px solid #4299e1; /* Dikurangi ketebalan dari 3px */
            position: relative;
        }
        .header::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 50%;
            transform: translateX(-50%);
            width: 40px; /* Dikurangi dari 60px */
            height: 2px;
            background: linear-gradient(90deg, #4299e1, #63b3ed);
            border-radius: 1px;
        }
        .company-name {
            font-size: 20px; /* Dikurangi dari 28px */
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 2px; /* Dikurangi dari 5px */
            letter-spacing: 0.3px; /* Dikurangi dari 0.5px */
        }
        .slip-title {
            font-size: 14px; /* Dikurangi dari 18px */
            font-weight: 600;
            color: #4299e1;
            text-transform: uppercase;
            letter-spacing: 0.5px; /* Dikurangi dari 1px */
        }
        .info-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px; /* Dikurangi dari 20px */
            margin-bottom: 15px; /* Dikurangi dari 30px */
            padding: 10px; /* Dikurangi dari 20px */
            background: linear-gradient(135deg, #f7fafc, #edf2f7);
            border-radius: 6px; /* Dikurangi dari 8px */
            border-left: 3px solid #4299e1; /* Dikurangi ketebalan dari 4px */
        }
        .info-item {
            display: flex;
            align-items: center;
            margin-bottom: 4px; /* Dikurangi dari 8px */
        }
        .info-label {
            font-weight: 600;
            color: #4a5568;
            min-width: 90px; /* Dikurangi dari 120px */
            margin-right: 5px; /* Dikurangi dari 10px */
            font-size: 9px; /* Dikurangi untuk compact */
        }
        .info-value {
            color: #2d3748;
            font-weight: 500;
            font-size: 9px; /* Dikurangi untuk compact */
        }
        .detail-section {
            margin-bottom: 15px; /* Dikurangi dari 30px */
        }
        .section-title {
            font-size: 12px; /* Dikurangi dari 14px */
            font-weight: 700;
            color: #4299e1;
            text-transform: uppercase;
            letter-spacing: 0.3px; /* Dikurangi dari 0.5px */
            margin-bottom: 8px; /* Dikurangi dari 15px */
            padding-bottom: 3px; /* Dikurangi dari 5px */
            border-bottom: 1px solid #e2e8f0; /* Dikurangi ketebalan dari 2px */
        }
        .detail-table {
            width: 100%;
            border-collapse: collapse;
            border-radius: 6px; /* Dikurangi dari 8px */
            overflow: hidden;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.03); /* Shadow lebih minimal */
        }
        .detail-table th {
            background: linear-gradient(135deg, #f7fafc, #edf2f7);
            font-weight: 600;
            color: #4a5568;
            padding: 8px 10px; /* Dikurangi dari 12px 15px */
            text-align: left;
            border-bottom: 1px solid #e2e8f0; /* Dikurangi ketebalan dari 2px */
            font-size: 9px; /* Dikurangi untuk compact */
        }
        .detail-table td {
            padding: 8px 10px; /* Dikurangi dari 12px 15px */
            border-bottom: 1px solid #e2e8f0;
            font-size: 9px; /* Dikurangi untuk compact */
        }
        .detail-table tr:nth-child(even) {
            background-color: #f8faff;
        }
        .detail-table .amount {
            text-align: right;
            font-weight: 500;
            color: #2d3748;
        }
        .summary-section {
            margin-top: 15px; /* Dikurangi dari 30px */
            padding: 10px; /* Dikurangi dari 20px */
            background: linear-gradient(135deg, #f0f9ff, #e6f3ff);
            border-radius: 6px; /* Dikurangi dari 8px */
            border: 1px solid #bee3f8;
        }
        .summary-table {
            width: 100%;
            border-collapse: collapse;
        }
        .summary-table td {
            padding: 6px 10px; /* Dikurangi dari 10px 15px */
            border-bottom: 1px solid #bee3f8;
            font-size: 9px; /* Dikurangi untuk compact */
        }
        .summary-label {
            font-weight: 600;
            color: #2d3748;
        }
        .summary-amount {
            text-align: right;
            font-weight: 600;
            color: #2d3748;
        }
        .take-home-pay {
            font-size: 13px; /* Dikurangi dari 16px */
            font-weight: 700;
            color: #2f855a;
            background: rgba(46, 125, 50, 0.08); /* Background lebih subtle */
            padding: 8px; /* Dikurangi dari 15px */
            border-radius: 4px; /* Dikurangi dari 6px */
            border: 1px solid #c6f6d5;
            margin-top: 5px; /* Tambah margin kecil untuk pemisah */
        }
        .signature-section {
            margin-top: 20px; /* Dikurangi dari 50px */
            text-align: center;
            padding-top: 15px; /* Dikurangi dari 30px */
            border-top: 1px solid #e2e8f0;
        }
        .signature-box {
            display: inline-block;
            text-align: center;
            margin: 0 10px; /* Dikurangi dari 20px */
            width: 150px; /* Dikurangi dari 200px */
        }
        .signature-line {
            border-top: 1px solid #4a5568;
            margin: 15px 0 5px 0; /* Dikurangi dari 30px 0 10px 0 */
            padding-top: 3px; /* Dikurangi dari 5px */
        }
        .signature-text {
            font-weight: 500;
            color: #4a5568;
            font-size: 9px; /* Dikurangi dari 11px */
        }
        @media print {
            body { 
                background: white; 
                padding: 5px; /* Lebih minimal untuk print */
            }
            .container { 
                box-shadow: none; 
                border: none; 
                padding: 15px; /* Dikurangi untuk PDF */
                margin: 0;
                max-width: none;
            }
            .header::after { display: none; } /* Hilangkan pseudo-element untuk hemat ruang print */
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="company-name">PT. Manajemen Karyawan Sejahtera</div>
            <div class="slip-title">Slip Gaji</div>
        </div>

        <div class="info-section">
            <div>
                <div class="info-item">
                    <span class="info-label">Nama Pegawai</span>
                    <span class="info-value">{{ $gaji->pegawai->nama ?? 'N/A' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Jabatan</span>
                    <span class="info-value">{{ $gaji->pegawai->jabatan->nama_jabatan ?? 'N/A' }}</span>
                </div>
            </div>
            <div>
                <div class="info-item">
                    <span class="info-label">Periode Gaji</span>
                    <span class="info-value">{{ \Carbon\Carbon::create()->month($gaji->bulan)->format('F') }} {{ $gaji->tahun }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Tanggal Cetak</span>
                    <span class="info-value">{{ now()->format('d F Y') }}</span>
                </div>
            </div>
        </div>

        <div class="detail-section">
            <div class="section-title">Pendapatan</div>
            <table class="detail-table">
                <thead>
                    <tr>
                        <th>Keterangan</th>
                        <th class="amount">Jumlah (Rp)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Gaji Pokok</td>
                        <td class="amount">{{ number_format($gaji->gaji_pokok, 0, ',', '.') }}</td>
                    </tr>
                    @foreach($gaji->tunjanganDetails as $tunjangan)
                    <tr>
                        <td>{{ $tunjangan->masterTunjangan->nama_tunjangan }}</td>
                        <td class="amount">{{ number_format($tunjangan->jumlah, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="detail-section">
            <div class="section-title">Potongan</div>
            <table class="detail-table">
                <thead>
                    <tr>
                        <th>Keterangan</th>
                        <th class="amount">Jumlah (Rp)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($gaji->potonganDetails as $potongan)
                    <tr>
                        <td>{{ $potongan->masterPotongan->nama_potongan }}</td>
                        <td class="amount">{{ number_format($potongan->jumlah, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td>Tidak ada potongan</td>
                        <td class="amount">0</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="summary-section">
            <table class="summary-table">
                <tr>
                    <td class="summary-label">Total Pendapatan</td>
                    <td class="summary-amount">{{ number_format($gaji->gaji_pokok + $gaji->total_tunjangan, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="summary-label">Total Potongan</td>
                    <td class="summary-amount">{{ number_format($gaji->total_potongan, 0, ',', '.') }}</td>
                </tr>
                <tr class="take-home-pay">
                    <td>GAJI BERSIH (Take Home Pay)</td>
                    <td>Rp {{ number_format($gaji->gaji_bersih, 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>

        <div class="signature-section">
            <div class="signature-box">
                <div class="signature-text">Hormat kami,</div>
                <div class="signature-line"></div>
                <div class="signature-text">Manajemen HRD</div>
            </div>
        </div>
    </div>
</body>
</html>