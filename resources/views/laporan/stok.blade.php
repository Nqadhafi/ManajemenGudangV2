@extends('layouts.adminlte')

@section('title', 'Laporan Stok')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Laporan Stok Barang</h3>
                <div class="card-tools">
                    <a href="{{ route('laporan.stok.print') }}" target="_blank" class="btn btn-success btn-sm">
                        <i class="fas fa-print"></i> Print
                    </a>
                </div>
            </div>
            <div class="card-body">
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
                            <th>Harga Rata-rata</th>     {{-- rata-rata per transaksi --}}
                            <th>Nilai Aset</th>          {{-- avg_harga * stok --}}
                          </tr>
                        </thead>
                        <tbody>
                        @php $totalAset = 0; @endphp
                        @foreach($barangs as $barang)
                          @php
                            $hargaAvg  = $barang->avg_harga;             // nullable
                            $nilaiAset = is_null($hargaAvg) ? null : ($hargaAvg * $barang->stok);
                            if (!is_null($nilaiAset)) $totalAset += $nilaiAset;
                          @endphp
                          <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $barang->nama_barang }}</td>
                            <td>{{ $barang->kategori->nama ?? '-' }}</td>
                            <td>{{ $barang->supplier->nama_supplier ?? '-' }}</td>
                            <td>{{ $barang->stok }}</td>
                            <td>{{ $barang->satuan }}</td>
                            <td>{{ $barang->stok_minimum }}</td>
                            <td>
                              @if(is_null($hargaAvg))
                                <span class="badge badge-secondary">Belum ada harga</span>
                              @else
                                Rp {{ number_format($hargaAvg, 2, ',', '.') }}
                              @endif
                            </td>
                            <td>
                              @if(is_null($nilaiAset))
                                —
                              @else
                                Rp {{ number_format($nilaiAset, 2, ',', '.') }}
                              @endif
                            </td>
                          </tr>
                        @endforeach
                        @if($barangs->isEmpty())
                            <tr>
                                <td colspan="9" class="text-center">Tidak ada data barang</td>
                            </tr>
                        @endif
                        </tbody>
                        <tfoot>
                          <tr>
                            <th colspan="8" class="text-right">Total Nilai Aset (barang yg sudah ada harga):</th>
                            <th>Rp {{ number_format($totalAset, 2, ',', '.') }}</th>
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
