@extends('layouts.adminlte')

@section('title', 'Laporan Transaksi')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Laporan Transaksi</h3>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('laporan.transaksi') }}" class="mb-3">
                    <div class="row">
                        <div class="col-md-3">
                            <select name="periode" class="form-control">
                                <option value="">Semua Periode</option>
                                <option value="harian" {{ request('periode') == 'harian' ? 'selected' : '' }}>Harian</option>
                                <option value="mingguan" {{ request('periode') == 'mingguan' ? 'selected' : '' }}>Mingguan</option>
                                <option value="bulanan" {{ request('periode') == 'bulanan' ? 'selected' : '' }}>Bulanan</option>
                                <option value="tahunan" {{ request('periode') == 'tahunan' ? 'selected' : '' }}>Tahunan</option>
                                <option value="custom" {{ request('periode') == 'custom' ? 'selected' : '' }}>Custom</option>
                            </select>
                        </div>
                        <div class="col-md-3" id="custom-date" style="display: {{ request('periode') == 'custom' ? 'block' : 'none' }};">
                            <input type="date" name="tanggal_awal" class="form-control" value="{{ request('tanggal_awal') }}">
                        </div>
                        <div class="col-md-3" id="custom-date-end" style="display: {{ request('periode') == 'custom' ? 'block' : 'none' }};">
                            <input type="date" name="tanggal_akhir" class="form-control" value="{{ request('tanggal_akhir') }}">
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <a href="{{ route('laporan.transaksi.print') }}?periode={{ request('periode') }}&tanggal_awal={{ request('tanggal_awal') }}&tanggal_akhir={{ request('tanggal_akhir') }}" 
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
                            </tr>
                            @endforeach
                            @if($transaksis->isEmpty())
                                <tr>
                                    <td colspan="7" class="text-center">Tidak ada data transaksi</td>
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
document.querySelector('select[name="periode"]').addEventListener('change', function() {
    if(this.value === 'custom') {
        document.getElementById('custom-date').style.display = 'block';
        document.getElementById('custom-date-end').style.display = 'block';
    } else {
        document.getElementById('custom-date').style.display = 'none';
        document.getElementById('custom-date-end').style.display = 'none';
    }
});
</script>
@endsection