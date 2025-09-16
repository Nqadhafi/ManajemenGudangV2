@extends('layouts.adminlte')

@section('title', 'Edit Transaksi Masuk')

@section('content')
<div class="row">
  <div class="col-md-8">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Edit Transaksi Masuk</h3>
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
              <input type="text" class="form-control" value="Masuk" disabled>
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

            <div class="form-group col-md-6">
              <label for="harga_satuan_display">Harga Satuan (Rp)</label>
              {{-- Input tampilan (berformat Rupiah) --}}
              <input type="text" id="harga_satuan_display"
                     class="form-control @error('harga_satuan') is-invalid @enderror"
                     inputmode="decimal" autocomplete="off" required>

              {{-- Input asli yang dikirim ke server --}}
              <input type="hidden" name="harga_satuan" id="harga_satuan"
                     value="{{ old('harga_satuan', $transaksi->harga_satuan) }}">

              @error('harga_satuan')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
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

@section('js')
<script>
(function(){
  const disp = document.getElementById('harga_satuan_display');
  const real = document.getElementById('harga_satuan');

  function formatRupiahDisplay(value) {
    if (value === '' || value == null) return '';
    // izinkan hanya angka, titik, koma
    let v = String(value).replace(/[^\d.,]/g, '');
    // normalisasi: titik -> koma untuk desimal tampilan
    v = v.replace(/\./g, ',');
    // pisah integer & desimal
    let [intPart, decPart] = v.split(',');
    intPart = (intPart || '').replace(/^0+(?=\d)/, ''); // buang nol depan
    // ribuan pakai titik
    intPart = intPart.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    if (decPart != null) decPart = decPart.slice(0, 2); // 2 desimal
    return 'Rp ' + (intPart || '0') + (decPart != null && decPart !== '' ? ',' + decPart : '');
  }

  function displayToNumeric(value) {
    if (!value) return '';
    // ambil angka + koma/titik
    let v = String(value).replace(/[^\d.,]/g, '');
    // hapus pemisah ribuan (titik), ubah koma jadi titik desimal
    v = v.replace(/\./g, '').replace(',', '.');
    return v;
  }

  // Set tampilan awal dari nilai hidden (old() / data transaksi)
  if (real && real.value) {
    disp.value = formatRupiahDisplay(real.value);
  }

  // Format saat mengetik
  disp.addEventListener('input', function() {
    const cursorEnd = this.selectionEnd || this.value.length;
    const beforeLen = this.value.length;

    const numeric = displayToNumeric(this.value);
    real.value = numeric; // sinkron nilai asli

    this.value = formatRupiahDisplay(numeric);

    // upaya menjaga posisi kursor agar tidak lompat jauh
    const afterLen = this.value.length;
    const delta = afterLen - beforeLen;
    const newPos = Math.max(0, cursorEnd + delta);
    this.setSelectionRange(newPos, newPos);
  });

  // Pastikan nilai numeric dikirim saat submit (jaga-jaga)
  (disp.form || document.querySelector('form')).addEventListener('submit', function(){
    real.value = displayToNumeric(disp.value);
  });
})();
</script>
@endsection
