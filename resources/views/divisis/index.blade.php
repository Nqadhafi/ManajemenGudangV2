@extends('layouts.adminlte')

@section('title', 'Data Divisi')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Divisi</h3>
                <div class="card-tools">
                    <a href="{{ route('divisis.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Tambah Divisi
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Divisi</th>
                                <th>Jumlah Karyawan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($divisis as $divisi)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $divisi->nama }}</td>
                                <td>{{ $divisi->karyawans->count() }}</td>
                                <td>
                                    <a href="{{ route('divisis.edit', $divisi->id) }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('divisis.destroy', $divisi->id) }}" method="POST" 
                                          style="display:inline;" onsubmit="return confirm('Yakin hapus divisi ini?')">
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