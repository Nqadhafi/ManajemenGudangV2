@extends('layouts.adminlte')

@section('title', 'Data Supplier')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Supplier</h3>
                <div class="card-tools">
                    <a href="{{ route('suppliers.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Tambah Supplier
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Supplier</th>
                                <th>No HP</th>
                                <th>Alamat</th>
                                <th>Jumlah Barang</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($suppliers as $supplier)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $supplier->nama_supplier }}</td>
                                <td>{{ $supplier->no_hp_supplier ?? '-' }}</td>
                                <td>{{ $supplier->alamat ?? '-' }}</td>
                                <td>{{ $supplier->barangs->count() }}</td>
                                <td>
                                    <a href="{{ route('suppliers.edit', $supplier->id) }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST" 
                                          style="display:inline;" onsubmit="return confirm('Yakin hapus supplier ini?')">
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