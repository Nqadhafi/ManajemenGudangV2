@extends('layouts.adminlte')

@section('title', 'History Transaksi')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">History Transaksi Saya</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Barang</th>
                                <th>Jenis</th>
                                <th>Jumlah</th>
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
                                <td>{{ $transaksi->keterangan ?? '-' }}</td>
                            </tr>
                            @endforeach
                            @if($transaksis->isEmpty())
                                <tr>
                                    <td colspan="6" class="text-center">Belum ada transaksi</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection