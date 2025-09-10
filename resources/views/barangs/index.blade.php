@extends('layouts.adminlte')

@section('title', 'Data Barang')

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Daftar Barang</h3>
        <div class="card-tools">
          <a href="{{ route('barangs.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Tambah Barang
          </a>
          <a href="{{ route('barangs.export') }}" class="btn btn-success btn-sm ml-1">
            <i class="fas fa-file-excel"></i> Export
          </a>
          <button type="button" class="btn btn-outline-primary btn-sm ml-1" data-toggle="modal" data-target="#importModal">
            <i class="fas fa-upload"></i> Import
          </button>
        </div>
      </div>

      <div class="card-body">
        {{-- Notifikasi khusus hasil import --}}
        @if(session('import_failures'))
          <div class="alert alert-warning">
            <strong>Beberapa baris gagal diimport:</strong>
            <ul class="mb-0">
              @foreach(session('import_failures') as $failure)
                <li>Baris {{ $failure->row() }}: {{ implode(', ', $failure->errors()) }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        @error('file')
          <div class="alert alert-danger">{{ $message }}</div>
        @enderror

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
                  @if($barang->stok <= 0)
                    <span class="badge badge-danger">Habis</span>
                  @elseif($barang->stok <= ($barang->stok_minimum * 1))
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
                  <form action="{{ route('barangs.destroy', $barang->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin hapus barang ini?')">
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

{{-- Modal Import --}}
<div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form action="{{ route('barangs.import') }}" method="POST" enctype="multipart/form-data" class="modal-content">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title" id="importModalLabel"><i class="fas fa-upload mr-1"></i> Import Barang</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
      </div>
      <div class="modal-body">
<p class="mb-2">Format header (berbasis NAMA, tanpa ID):</p>
<pre class="bg-light p-2 rounded mb-3">no,nama_barang,satuan,stok,stok_minimum,kategori_nama,supplier_nama</pre>

<div class="custom-file mb-3">
  <input type="file" name="file" class="custom-file-input" id="importBarang" accept=".xlsx,.xls,.csv" required>
  <label class="custom-file-label" for="importBarang">Pilih file...</label>
</div>

<div class="form-check mb-2">
  <input class="form-check-input" type="checkbox" name="auto_create" id="autoCreate" value="1">
  <label class="form-check-label" for="autoCreate">
    Buat kategori/supplier baru secara otomatis jika belum ada
  </label>
</div>

<div class="form-check">
  <input class="form-check-input" type="checkbox" name="case_sensitive" id="caseSensitive" value="1">
  <label class="form-check-label" for="caseSensitive">
    Pencocokan nama <strong>case-sensitive</strong>
  </label>
</div>

      </div>
      <div class="modal-footer">
        <div class="d-flex align-items-center mr-auto">
          <a href="{{ route('kategoris.export') }}" class="btn btn-link p-0 mr-3">
            <i class="fas fa-download"></i> Unduh Master Kategori
          </a>
          <a href="{{ route('suppliers.export') }}" class="btn btn-link p-0">
            <i class="fas fa-download"></i> Unduh Master Supplier
          </a>
        </div>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">Import</button>
      </div>
    </form>
  </div>
</div>
@endsection

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function () {
  var input = document.getElementById('importBarang');
  if (input) {
    input.addEventListener('change', function(){
      var label = document.querySelector('label[for="importBarang"]');
      if (label && this.files.length) label.textContent = this.files[0].name;
    });
  }
});
</script>
@endsection
