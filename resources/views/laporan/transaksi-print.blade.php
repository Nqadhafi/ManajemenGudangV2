<!DOCTYPE html>
<html>
<head>
    <title>Laporan Transaksi</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .header { text-align: center; margin-bottom: 20px; }
        .badge { padding: 3px 6px; border-radius: 3px; color: white; font-size: 12px; }
        .badge-success { background-color: #28a745; }
        .badge-danger { background-color: #dc3545; }

        /* Tambahan untuk tanda tangan */
        .signature {
            width: 100%;
            margin-top: 50px;
            display: flex;
            justify-content: flex-end;
        }
        .signature-block {
            text-align: center;
            width: 250px;
        }
        .signature-space {
            height: 80px; /* ruang kosong untuk tanda tangan */
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN TRANSAKSI</h2>
        <p>
            @if($periode == 'harian')
                Periode: {{ date('d/m/Y') }}
            @elseif($periode == 'mingguan')
                Periode: Minggu ini
            @elseif($periode == 'bulanan')
                Periode: Bulan ini
            @elseif($periode == 'tahunan')
                Periode: Tahun ini
            @elseif($periode == 'custom')
                Periode: {{ request('tanggal_awal') }} s/d {{ request('tanggal_akhir') }}
            @else
                Periode: Semua
            @endif
        </p>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Barang</th>
                <th>Jenis</th>
                <th>Jumlah</th>
                <th>Karyawan</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaksis as $transaksi)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $transaksi->tanggal_transaksi->format('d/m/Y H:i') }}</td>
                <td>{{ $transaksi->barang->nama_barang ?? '-' }}</td>
                <td class="text-center">
                    <span class="badge {{ $transaksi->jenis_transaksi == 'masuk' ? 'badge-success' : 'badge-danger' }}">
                        {{ ucfirst($transaksi->jenis_transaksi) }}
                    </span>
                </td>
                <td class="text-right">{{ $transaksi->jumlah }}</td>
                <td>{{ $transaksi->karyawan->nama ?? '-' }}</td>
                <td>{{ $transaksi->keterangan ?? '-' }}</td>
            </tr>
            @endforeach
            @if($transaksis->isEmpty())
            <tr>
                <td colspan="7" class="text-center">Tidak ada data transaksi</td>
            </tr>
            @endif
        </tbody>
    </table>

    <!-- Blok tanda tangan -->
    <div class="signature">
        <div class="signature-block">
            <p>Surakarta, {{ date('d/m/Y') }}</p>
            <p><strong>Admin Gudang</strong></p>
            <div class="signature-space"></div>
            <p><u>Lia Setianingrum</u></p>
        </div>
    </div>
    
    <script>
        window.print();
    </script>
</body>
</html>
