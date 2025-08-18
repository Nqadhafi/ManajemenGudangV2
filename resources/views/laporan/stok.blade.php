@extends('layouts.adminlte')

@section('title', 'Laporan Stok')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Laporan Stok Barang</h3>
                <div class="card-tools">
                    <a href="{{ route('laporan.stok.print') }}" target="_blank" class="btn btn-success btn-sm">
                        <i class="fas fa-print"></i> Print
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
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
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $barang->nama_barang }}</td>
                                <td>{{ $barang->kategori->nama ?? '-' }}</td>
                                <td>{{ $barang->supplier->nama_supplier ?? '-' }}</td>
                                <td>{{ $barang->stok }}</td>
                                <td>{{ $barang->satuan }}</td>
                                <td>{{ $barang->stok_minimum }}</td>
                                <td>
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
                </div>
            </div>
        </div>
    </div>
</div>
@endsection