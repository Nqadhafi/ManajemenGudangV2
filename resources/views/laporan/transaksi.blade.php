@extends('layouts.adminlte')

@section('title', 'Laporan Transaksi')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">Laporan Transaksi</h3>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('laporan.transaksi') }}" class="mb-3">
                    <div class="row">
                        {{-- Periode --}}
                        <div class="col-md-3 mb-2">
                            <select name="periode" class="form-control" id="periodeSelect">
                                <option value="">Semua Periode</option>
                                <option value="harian"   {{ request('periode')=='harian'   ? 'selected' : '' }}>Harian</option>
                                <option value="mingguan" {{ request('periode')=='mingguan' ? 'selected' : '' }}>Mingguan</option>
                                <option value="bulanan"  {{ request('periode')=='bulanan'  ? 'selected' : '' }}>Bulanan</option>
                                <option value="tahunan"  {{ request('periode')=='tahunan'  ? 'selected' : '' }}>Tahunan</option>
                                <option value="custom"   {{ request('periode')=='custom'   ? 'selected' : '' }}>Custom</option>
                            </select>
                        </div>

                        {{-- Tanggal Custom --}}
                        <div class="col-md-3 mb-2" id="custom-date" style="display: {{ request('periode')=='custom' ? 'block' : 'none' }};">
                            <input type="date" name="tanggal_awal" class="form-control" value="{{ request('tanggal_awal') }}">
                        </div>
                        <div class="col-md-3 mb-2" id="custom-date-end" style="display: {{ request('periode')=='custom' ? 'block' : 'none' }};">
                            <input type="date" name="tanggal_akhir" class="form-control" value="{{ request('tanggal_akhir') }}">
                        </div>

                        {{-- Jenis Transaksi --}}
                        <div class="col-md-3 mb-2">
                            <select name="jenis" class="form-control">
                                <option value="">Semua Jenis</option>
                                <option value="masuk"  {{ request('jenis')=='masuk'  ? 'selected' : '' }}>Masuk</option>
                                <option value="keluar" {{ request('jenis')=='keluar' ? 'selected' : '' }}>Keluar</option>
                            </select>
                        </div>

                        {{-- Barang --}}
                        <div class="col-md-3 mb-2">
                            <select name="barang_id" class="form-control">
                                <option value="">Semua Barang</option>
                                @isset($barangsList)
                                    @foreach($barangsList as $b)
                                        <option value="{{ $b->id }}" {{ (string)request('barang_id')===(string)$b->id ? 'selected' : '' }}>
                                            {{ $b->nama_barang }}
                                        </option>
                                    @endforeach
                                @endisset
                            </select>
                        </div>

                        {{-- Karyawan --}}
                        <div class="col-md-3 mb-2">
                            <select name="karyawan_id" class="form-control">
                                <option value="">Semua Karyawan</option>
                                @isset($karyawansList)
                                    @foreach($karyawansList as $k)
                                        <option value="{{ $k->id }}" {{ (string)request('karyawan_id')===(string)$k->id ? 'selected' : '' }}>
                                            {{ $k->nama }}
                                        </option>
                                    @endforeach
                                @endisset
                            </select>
                        </div>

                        {{-- Tombol Aksi --}}
                        <div class="col-md-3 mb-2 d-flex align-items-start">
                            <button type="submit" class="btn btn-primary mr-2">Filter</button>

                            {{-- Link Print dengan semua query ikut serta --}}
                            @php
                                $qs = http_build_query([
                                    'periode'        => request('periode'),
                                    'tanggal_awal'   => request('tanggal_awal'),
                                    'tanggal_akhir'  => request('tanggal_akhir'),
                                    'jenis'          => request('jenis'),
                                    'barang_id'      => request('barang_id'),
                                    'karyawan_id'    => request('karyawan_id'),
                                ]);
                            @endphp
                            <a href="{{ route('laporan.transaksi.print') }}?{{ $qs }}"
                               target="_blank" class="btn btn-success">
                                <i class="fas fa-print"></i> Print
                            </a>
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Barang</th>
                                <th>Jenis</th>
                                <th>Jumlah</th>
                                <th>Karyawan</th>
                                <th>Keterangan</th>
                                <th>Edit</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transaksis as $transaksi)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $transaksi->tanggal_transaksi->format('d/m/Y H:i') }}</td>
                                <td>{{ $transaksi->barang->nama_barang ?? '-' }}</td>
                                <td>
                                    <span class="badge badge-{{ $transaksi->jenis_transaksi == 'masuk' ? 'success' : 'danger' }}">
                                        {{ ucfirst($transaksi->jenis_transaksi) }}
                                    </span>
                                </td>
                                <td>{{ $transaksi->jumlah }}</td>
                                <td>{{ $transaksi->karyawan->nama ?? '-' }}</td>
                                <td>{{ $transaksi->keterangan ?? '-' }}</td>
                                <td class="text-nowrap">
                                    <a href="{{ route('transaksi.edit', $transaksi->id) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                            @if($transaksis->isEmpty())
                                <tr>
                                    <td colspan="8" class="text-center">Tidak ada data transaksi</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// toggle tanggal custom
document.getElementById('periodeSelect').addEventListener('change', function() {
    const on = this.value === 'custom';
    document.getElementById('custom-date').style.display = on ? 'block' : 'none';
    document.getElementById('custom-date-end').style.display = on ? 'block' : 'none';
});
</script>
@endsection
