<!DOCTYPE html>
<html>
<head>
    <title>Laporan Stok Barang</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { border: 1px solid #000; padding: 6px 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .header { text-align: center; margin-bottom: 8px; }
        .subheader { margin-top: 6px; font-size: 12px; }
        .muted { color: #666; }

        /* Tanda tangan */
        .signature { width: 100%; margin-top: 40px; display: flex; justify-content: flex-end; }
        .signature-block { text-align: center; width: 260px; }
        .signature-space { height: 70px; }
    </style>
</head>
<body>
    <div class="header">
        <h2 style="margin:0;">LAPORAN STOK BARANG</h2>
        <div class="muted">Dicetak: {{ now()->format('d/m/Y H:i') }}</div>
    </div>

    {{-- Ringkasan filter (mengikuti query q/kategori_id/supplier_id) --}}
    @php
        $q = request('q');
        $kategoriId = request('kategori_id');
        $supplierId = request('supplier_id');

        // Optional: kalau mau tampilkan nama kategori/supplier, bisa ambil dari relasi koleksi $barangs pertama yang cocok.
        $kategoriNama = null;
        $supplierNama = null;

        if ($kategoriId) {
            foreach ($barangs as $b) {
                if ($b->id_kategori == $kategoriId && optional($b->kategori)->nama) { $kategoriNama = $b->kategori->nama; break; }
            }
        }
        if ($supplierId) {
            foreach ($barangs as $b) {
                if ($b->id_supplier == $supplierId && optional($b->supplier)->nama_supplier) { $supplierNama = $b->supplier->nama_supplier; break; }
            }
        }
    @endphp

    @if($q || $kategoriId || $supplierId)
    <div class="subheader">
        <strong>Filter:</strong>
        @if($q) Pencarian = "{{ $q }}"@endif
        @if($q && ($kategoriId || $supplierId)) • @endif
        @if($kategoriId) Kategori = "{{ $kategoriNama ?? ('#'.$kategoriId) }}" @endif
        @if(($q || $kategoriId) && $supplierId) • @endif
        @if($supplierId) Supplier = "{{ $supplierNama ?? ('#'.$supplierId) }}" @endif
    </div>
    @endif

    @php $totalAset = 0; @endphp
    <table>
        <thead>
            <tr>
                <th style="width:40px;">No</th>
                <th>Nama Barang</th>
                <th>Kategori</th>
                <th>Supplier</th>
                <th class="text-right" style="width:70px;">Stok</th>
                <th style="width:80px;">Satuan</th>
                <th class="text-right" style="width:140px;">Harga Rata-rata</th>
                <th class="text-right" style="width:160px;">Nilai Aset</th>
            </tr>
        </thead>
        <tbody>
            @forelse($barangs as $barang)
                @php
                    $avg = $barang->avg_harga; // nullable
                    $nilai = is_null($avg) ? null : ($avg * $barang->stok);
                    if (!is_null($nilai)) $totalAset += $nilai;
                @endphp
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $barang->nama_barang }}</td>
                    <td>{{ $barang->kategori->nama ?? '-' }}</td>
                    <td>{{ $barang->supplier->nama_supplier ?? '-' }}</td>
                    <td class="text-right">{{ number_format($barang->stok, 0, ',', '.') }}</td>
                    <td>{{ $barang->satuan }}</td>
                    <td class="text-right">
                        @if(is_null($avg))
                            —
                        @else
                            Rp {{ number_format($avg, 2, ',', '.') }}
                        @endif
                    </td>
                    <td class="text-right">
                        @if(is_null($nilai))
                            —
                        @else
                            Rp {{ number_format($nilai, 2, ',', '.') }}
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">Tidak ada data barang</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <th colspan="7" class="text-right">Total Nilai Aset (barang yg sudah ada harga):</th>
                <th class="text-right">Rp {{ number_format($totalAset, 2, ',', '.') }}</th>
            </tr>
        </tfoot>
    </table>

    {{-- Tanda tangan --}}
    <div class="signature">
        <div class="signature-block">
            <p>Surakarta, {{ date('d/m/Y') }}</p>
            <p><strong>Admin Gudang</strong></p>
            <div class="signature-space"></div>
            <p><u>Lia Setianingrum</u></p>
        </div>
    </div>

    <script>window.print();</script>
</body>
</html>
