@extends('layouts.adminlte')

@section('title', 'Transaksi Masuk')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Transaksi Masuk Barang</h3>
            </div>
            <form action="{{ route('transaksi.masuk') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label for="id_barang">Barang</label>
                        <select name="id_barang" class="form-control @error('id_barang') is-invalid @enderror" required>
                            <option value="">Pilih Barang</option>
                            @foreach($barangs as $barang)
                                <option value="{{ $barang->id }}" {{ old('id_barang') == $barang->id ? 'selected' : '' }}>
                                    {{ $barang->nama_barang }} (Stok: {{ $barang->stok }})
                                </option>
                            @endforeach
                        </select>
                        @error('id_barang')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="id_karyawan">Karyawan</label>
                        <select name="id_karyawan" class="form-control @error('id_karyawan') is-invalid @enderror" required>
                            <option value="">Pilih Karyawan</option>
                            @foreach($karyawans as $karyawan)
                                <option value="{{ $karyawan->id }}" {{ old('id_karyawan') == $karyawan->id ? 'selected' : '' }}>
                                    {{ $karyawan->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_karyawan')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="jumlah">Jumlah</label>
                        <input type="number" name="jumlah" class="form-control @error('jumlah') is-invalid @enderror" 
                               value="{{ old('jumlah') }}" min="1" required>
                        @error('jumlah')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                    <label for="harga_satuan_display">Harga Satuan (Rp)</label>
                    {{-- input tampilan (formatted) --}}
                    <input type="text" id="harga_satuan_display"
                            class="form-control @error('harga_satuan') is-invalid @enderror"
                            inputmode="decimal" autocomplete="off" required>
                    {{-- input asli yang dikirim ke server --}}
                    <input type="hidden" name="harga_satuan" id="harga_satuan"
                            value="{{ old('harga_satuan') }}">
                    @error('harga_satuan')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>


                    <div class="form-group">
                        <label for="keterangan">Keterangan</label>
                        <textarea name="keterangan" class="form-control @error('keterangan') is-invalid @enderror">{{ old('keterangan') }}</textarea>
                        @error('keterangan')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Simpan Transaksi</button>
                    <a href="{{ route('dashboard') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
(function(){
  const disp = document.getElementById('harga_satuan_display');
  const real = document.getElementById('harga_satuan');

  // Format tampilan ke Rupiah (Rp 1.234,56)
  function formatRupiahDisplay(value) {
    if (value === '' || value == null) return '';
    // terima angka, titik, koma; sisanya buang
    let v = String(value).replace(/[^\d.,]/g, '');
    // normalisasi: pakai koma sebagai desimal di tampilan
    v = v.replace(/\./g, ',');
    // pisah integer & decimal
    let [intPart, decPart] = v.split(',');
    intPart = (intPart || '').replace(/^0+(?=\d)/, ''); // buang 0 depan
    // ribuan pakai titik
    intPart = intPart.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    if (decPart != null) decPart = decPart.slice(0, 2); // batasi 2 desimal
    return 'Rp ' + (intPart || '0') + (decPart != null && decPart !== '' ? ',' + decPart : '');
  }

  // Konversi tampilan ke nilai numeric (string) untuk dikirim (1234,56 -> 1234.56)
  function displayToNumeric(value) {
    if (!value) return '';
    // ambil angka + koma/titik
    let v = String(value).replace(/[^\d.,]/g, '');
    // buang pemisah ribuan (titik), ubah koma menjadi titik desimal
    v = v.replace(/\./g, '').replace(',', '.');
    return v;
  }

  // sinkron awal dari old() agar tampil formatted
  if (real && real.value) {
    disp.value = formatRupiahDisplay(real.value);
  }

  // format saat mengetik
  disp.addEventListener('input', function(e) {
    const cursorEnd = this.selectionEnd;
    const beforeLen = this.value.length;

    const numeric = displayToNumeric(this.value);
    real.value = numeric;

    this.value = formatRupiahDisplay(numeric);

    // upaya menjaga posisi kursor
    const afterLen = this.value.length;
    const delta = afterLen - beforeLen;
    this.setSelectionRange(cursorEnd + delta, cursorEnd + delta);
  });

  // pastikan hidden terisi sebelum submit
  disp.form?.addEventListener('submit', function(){
    real.value = displayToNumeric(disp.value);
  });
})();
</script>
@endpush
