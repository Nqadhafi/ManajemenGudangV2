<!DOCTYPE html>
<html>
<head>
    <title>Laporan Stok Barang</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .header { text-align: center; margin-bottom: 20px; }
        .badge { padding: 3px 6px; border-radius: 3px; color: white; font-size: 12px; }
        .badge-danger { background-color: #dc3545; }
        .badge-warning { background-color: #ffc107; color: #000; }
        .badge-success { background-color: #28a745; }
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN STOK BARANG</h2>
        <p>Periode: {{ date('d/m/Y') }}</p>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Barang</th>
                <th>Kategori</th>
                <th>Supplier</th>
                <th>Stok</th>
                <th>Satuan</th>
                <th>Stok Min</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($barangs as $barang)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $barang->nama_barang }}</td>
                <td>{{ $barang->kategori->nama ?? '-' }}</td>
                <td>{{ $barang->supplier->nama_supplier ?? '-' }}</td>
                <td class="text-right">{{ $barang->stok }}</td>
                <td>{{ $barang->satuan }}</td>
                <td class="text-right">{{ $barang->stok_minimum }}</td>
                <td class="text-center">
                    @if($barang->stok <= $barang->stok_minimum)
                        <span class="badge badge-danger">Habis</span>
                    @elseif($barang->stok <= ($barang->stok_minimum * 1.5))
                        <span class="badge badge-warning">Menipis</span>
                    @else
                        <span class="badge badge-success">Aman</span>
                    @endif
                </td>
            </tr>
            @endforeach
            @if($barangs->isEmpty())
            <tr>
                <td colspan="8" class="text-center">Tidak ada data barang</td>
            </tr>
            @endif
        </tbody>
    </table>
    
    <script>
        window.print();
    </script>
</body>
</html>