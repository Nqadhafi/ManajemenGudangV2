@extends('layouts.adminlte')

@section('title', 'Edit Transaksi Keluar')

@section('content')
<div class="row">
  <div class="col-md-8">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Edit Transaksi Keluar</h3>
        <div class="card-tools">
          <a href="{{ route('barangs.kartu-stok', $barang->id) }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Kembali
          </a>
        </div>
      </div>
      <form action="{{ route('transaksi.update', $transaksi->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
          <div class="form-row">
            <div class="form-group col-md-6">
              <label>Barang</label>
              <input type="text" class="form-control" value="{{ $barang->nama_barang }}" disabled>
            </div>
            <div class="form-group col-md-6">
              <label>Jenis</label>
              <input type="text" class="form-control" value="Keluar" disabled>
            </div>
          </div>

          <div class="form-group">
            <label for="id_karyawan">Karyawan</label>
            <select name="id_karyawan" id="id_karyawan" class="form-control @error('id_karyawan') is-invalid @enderror" required>
              @foreach($karyawans as $k)
                <option value="{{ $k->id }}" {{ $transaksi->id_karyawan == $k->id ? 'selected' : '' }}>
                  {{ $k->nama }}
                </option>
              @endforeach
            </select>
            @error('id_karyawan')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>

          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="jumlah">Jumlah</label>
              <input type="number" name="jumlah" id="jumlah" min="1"
                     value="{{ old('jumlah', $transaksi->jumlah) }}"
                     class="form-control @error('jumlah') is-invalid @enderror" required>
              @error('jumlah')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
          </div>

          <div class="form-group">
            <label for="keterangan">Keterangan</label>
            <textarea name="keterangan" id="keterangan" rows="2" class="form-control">{{ old('keterangan', $transaksi->keterangan) }}</textarea>
          </div>
        </div>
        <div class="card-footer">
          <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
