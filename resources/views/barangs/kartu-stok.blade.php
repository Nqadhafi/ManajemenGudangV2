@extends('layouts.adminlte')

@section('title', 'Kartu Stok - ' . $barang->nama_barang)

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Kartu Stok - {{ $barang->nama_barang }}</h3>
                <div class="card-tools">
                    <a href="{{ route('barangs.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <strong>Nama Barang:</strong><br>
                        {{ $barang->nama_barang }}
                    </div>
                    <div class="col-md-3">
                        <strong>Kategori:</strong><br>
                        {{ $barang->kategori->nama ?? '-' }}
                    </div>
                    <div class="col-md-3">
                        <strong>Supplier:</strong><br>
                        {{ $barang->supplier->nama_supplier ?? '-' }}
                    </div>
                    <div class="col-md-3">
                        <strong>Stok Sekarang:</strong><br>
                        <span class="badge badge-{{ $barang->stok_status }}">
                            {{ $barang->stok }} {{ $barang->satuan }}
                        </span>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Jenis</th>
                                <th>Jumlah</th>
                                <th>Harga</th>
                                <th>Karyawan</th>
                                <th>Keterangan</th>
                                <th>Stok Akhir</th>
                                <th>Ubah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $stok_berjalan = 0; @endphp
                            @foreach($transaksis->reverse() as $transaksi)
                                @php
                                    if ($transaksi->jenis_transaksi == 'masuk') {
                                        $stok_berjalan += $transaksi->jumlah;
                                    } else {
                                        $stok_berjalan -= $transaksi->jumlah;
                                    }
                                @endphp
                                <tr>
                                    <td>{{ $transaksi->tanggal_transaksi->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <span class="badge badge-{{ $transaksi->jenis_transaksi == 'masuk' ? 'success' : 'danger' }}">
                                            {{ ucfirst($transaksi->jenis_transaksi) }}
                                        </span>
                                    </td>
                                    <td>{{ $transaksi->jumlah }}</td>
                                    <td>
                                        @if($transaksi->jenis_transaksi === 'masuk' && !is_null($transaksi->harga_satuan))
                                            Rp {{ number_format($transaksi->harga_satuan, 2, ',', '.') }}
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td>{{ $transaksi->karyawan->nama ?? '-' }}</td>
                                    <td>{{ $transaksi->keterangan ?? '-' }}</td>
                                    <td>{{ $stok_berjalan }}</td>
                                    <td class="text-nowrap">
                                    <a href="{{ route('transaksi.edit', $transaksi->id) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    </td>
                                </tr>
                            @endforeach
                            @if($transaksis->isEmpty())
                                <tr>
                                    <td colspan="7" class="text-center">Belum ada transaksi</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
