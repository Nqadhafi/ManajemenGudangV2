@extends('layouts.adminlte')

@section('title', 'Data Supplier')

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Daftar Supplier</h3>
        <div class="card-tools">
          <a href="{{ route('suppliers.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Tambah Supplier
          </a>
          <a href="{{ route('suppliers.export') }}" class="btn btn-success btn-sm ml-1">
            <i class="fas fa-file-excel"></i> Export
          </a>
          <button type="button" class="btn btn-outline-primary btn-sm ml-1" data-toggle="modal" data-target="#importSupplierModal">
            <i class="fas fa-upload"></i> Import
          </button>
        </div>
      </div>

      <div class="card-body">
        {{-- Notifikasi hasil import --}}
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
                  <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin hapus supplier ini?')">
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

{{-- Modal Import Supplier --}}
<div class="modal fade" id="importSupplierModal" tabindex="-1" role="dialog" aria-labelledby="importSupplierModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form action="{{ route('suppliers.import') }}" method="POST" enctype="multipart/form-data" class="modal-content">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title" id="importSupplierModalLabel">
          <i class="fas fa-upload mr-1"></i> Import Supplier
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
      </div>
      <div class="modal-body">
        <p class="mb-2">Unggah file Excel/CSV dengan header:</p>
        <pre class="bg-light p-2 rounded mb-3">id,nama_supplier,no_hp_supplier,alamat</pre>
        <div class="custom-file">
          <input type="file" name="file" class="custom-file-input" id="importSupplier" accept=".xlsx,.xls,.csv" required>
          <label class="custom-file-label" for="importSupplier">Pilih file...</label>
        </div>
      </div>
      <div class="modal-footer">
        <a href="{{ route('suppliers.export') }}" class="btn btn-link p-0 mr-auto">
          <i class="fas fa-download"></i> Unduh contoh (export saat ini)
        </a>
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
  var input = document.getElementById('importSupplier');
  if (input) {
    input.addEventListener('change', function(){
      var label = document.querySelector('label[for="importSupplier"]');
      if (label && this.files.length) label.textContent = this.files[0].name;
    });
  }
});
</script>
@endsection
