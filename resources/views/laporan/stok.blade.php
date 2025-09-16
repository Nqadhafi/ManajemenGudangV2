@extends('layouts.adminlte')

@section('title', 'Laporan Stok')

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Laporan Stok Barang</h3>
        <div class="card-tools d-flex align-items-center">
          {{-- Fixed filter: Kategori --}}
          <div class="input-group input-group-sm mr-2" style="width: 220px;">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="fas fa-layer-group"></i></span>
            </div>
            <select id="filterKategori" class="form-control">
              <option value="">Semua Kategori</option>
              @foreach($kategoris as $kat)
                <option value="{{ $kat->id }}">{{ $kat->nama }}</option>
              @endforeach
            </select>
          </div>

          {{-- Fixed filter: Supplier --}}
          <div class="input-group input-group-sm mr-2" style="width: 240px;">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="fas fa-truck"></i></span>
            </div>
            <select id="filterSupplier" class="form-control">
              <option value="">Semua Supplier</option>
              @foreach($suppliers as $sup)
                <option value="{{ $sup->id }}">{{ $sup->nama_supplier }}</option>
              @endforeach
            </select>
          </div>

          {{-- Live search by nama barang --}}
          <div class="input-group input-group-sm mr-2" style="width: 260px;">
            <input type="text" id="searchNama" class="form-control" placeholder="Cari nama barang…">
            <div class="input-group-append">
              <button class="btn btn-default" type="button" id="clearSearch" title="Bersihkan">
                <i class="fas fa-times"></i>
              </button>
            </div>
          </div>

          {{-- Print mengikuti filter --}}
          @php
            $qs = http_build_query([
              'q'            => request('q'),
              'kategori_id'  => request('kategori_id'),
              'supplier_id'  => request('supplier_id'),
            ]);
          @endphp
          <a id="printBtn" href="{{ route('laporan.stok.print') }}?{{ $qs }}" target="_blank" class="btn btn-success btn-sm">
            <i class="fas fa-print"></i> Print
          </a>
        </div>
      </div>

      <div class="card-body">
        <div class="table-responsive position-relative">
          {{-- Spinner overlay --}}
          <div id="tblSpinner" class="position-absolute w-100 h-100 d-none"
               style="top:0;left:0;background:rgba(255,255,255,.6);display:flex;align-items:center;justify-content:center;z-index:5;">
            <i class="fas fa-spinner fa-spin fa-2x text-muted"></i>
          </div>

          <table class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>No</th>
                <th>Nama Barang</th>
                <th>Kategori</th>
                <th>Supplier</th>
                <th>Stok</th>
                <th>Satuan</th>
                <th>Harga Rata-rata</th>  {{-- rata-rata per transaksi --}}
                <th>Nilai Aset</th>       {{-- avg_harga * stok --}}
              </tr>
            </thead>
            <tbody id="stokTbody">
              @include('laporan._stok_rows', ['barangs' => $barangs])
            </tbody>
            <tfoot>
              @php
                $totalAset = 0;
                foreach ($barangs as $b) {
                  $avg = $b->avg_harga ?? null;
                  if (!is_null($avg)) $totalAset += ($avg * $b->stok);
                }
              @endphp
              <tr>
                <th colspan="7" class="text-right">Total Nilai Aset (barang yg sudah ada harga):</th>
                <th id="totalAsetCell">Rp {{ number_format($totalAset, 2, ',', '.') }}</th>
              </tr>
            </tfoot>
          </table>
          <small class="text-muted">* Total hanya menjumlah barang dengan harga rata-rata (avg_harga) yang sudah terbentuk.</small>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const inputNama   = document.getElementById('searchNama');
  const btnClear    = document.getElementById('clearSearch');
  const selKat      = document.getElementById('filterKategori');
  const selSup      = document.getElementById('filterSupplier');
  const tbody       = document.getElementById('stokTbody');
  const spinner     = document.getElementById('tblSpinner');
  const totalCell   = document.getElementById('totalAsetCell');
  const printBtn    = document.getElementById('printBtn');

  let timer = null;
  const DEBOUNCE = 300;

  function setLoading(on) {
    spinner?.classList.toggle('d-none', !on);
  }

  function rebuildPrintHref(q, kategoriId, supplierId) {
    const base = "{{ route('laporan.stok.print') }}";
    const params = new URLSearchParams();
    if (q) params.set('q', q);
    if (kategoriId) params.set('kategori_id', kategoriId);
    if (supplierId) params.set('supplier_id', supplierId);
    printBtn.href = params.toString() ? (base + '?' + params.toString()) : base;
  }

  async function fetchRows() {
    const q          = inputNama?.value.trim() || '';
    const kategoriId = selKat?.value || '';
    const supplierId = selSup?.value || '';

    const url = new URL("{{ route('laporan.stok.search') }}", window.location.origin);
    if (q) url.searchParams.set('q', q);
    if (kategoriId) url.searchParams.set('kategori_id', kategoriId);
    if (supplierId) url.searchParams.set('supplier_id', supplierId);

    setLoading(true);
    try {
      const res = await fetch(url.toString(), {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
      });
      const data = await res.json();
      tbody.innerHTML = data.rows || '';
      totalCell.textContent = data.total_formatted || 'Rp 0,00';
      rebuildPrintHref(q, kategoriId, supplierId);
    } catch (e) {
      console.error(e);
    } finally {
      setLoading(false);
    }
  }

  // Debounce: nama barang saja
  inputNama?.addEventListener('input', function(){
    if (timer) clearTimeout(timer);
    timer = setTimeout(fetchRows, DEBOUNCE);
  });

  inputNama?.addEventListener('keydown', function(e){
    if (e.key === 'Escape') {
      inputNama.value = '';
      fetchRows();
      e.preventDefault();
    }
  });

  // Fixed filters: langsung fetch saat berubah
  selKat?.addEventListener('change', fetchRows);
  selSup?.addEventListener('change', fetchRows);

  // Clear tombol: hanya hapus teks search
  btnClear?.addEventListener('click', function(){
    inputNama.value = '';
    fetchRows();
    inputNama.focus();
  });
});
</script>
@endsection
