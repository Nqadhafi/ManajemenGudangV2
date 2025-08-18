@extends('layouts.adminlte')

@section('title', 'Data Barang')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Barang</h3>
                <div class="card-tools">
                    <a href="{{ route('barangs.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Tambah Barang
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
                                <th>Aksi</th>
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
                                <td>
                                    <a href="{{ route('barangs.kartu-stok', $barang->id) }}" class="btn btn-info btn-sm" title="Kartu Stok">
                                        <i class="fas fa-list"></i>
                                    </a>
                                    <a href="{{ route('barangs.edit', $barang->id) }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('barangs.destroy', $barang->id) }}" method="POST" 
                                          style="display:inline;" onsubmit="return confirm('Yakin hapus barang ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection