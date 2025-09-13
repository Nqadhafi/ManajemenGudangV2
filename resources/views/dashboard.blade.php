@extends('layouts.adminlte')

@section('title', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $totalBarang }}</h3>
                <p>Total Barang</p>
            </div>
            <div class="icon">
                <i class="fas fa-box"></i>
            </div>
        </div>
    </div>

    {{-- 
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $totalStok }}</h3>
                <p>Total Stok</p>
            </div>
            <div class="icon">
                <i class="fas fa-cubes"></i>
            </div>
        </div>
    </div>
    --}}

    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $barangHampirHabis }}</h3>
                <p>Barang Hampir Habis</p>
            </div>
            <div class="icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $transaksiHariIni }}</h3>
                <p>Transaksi Hari Ini</p>
            </div>
            <div class="icon">
                <i class="fas fa-exchange-alt"></i>
            </div>
        </div>
    </div>

    {{-- Total Nilai Aset --}}
    <div class="col-lg-3 col-6">
        <div class="small-box bg-teal">
            <div class="inner">
                <h3>Rp {{ number_format($totalNilaiAset, 2, ',', '.') }}</h3>
                <p>Total Nilai Aset*</p>
            </div>
            <div class="icon"><i class="fas fa-coins"></i></div>
            <div class="small text-right pr-2 pb-2">* hanya barang yg sudah punya harga</div>
        </div>
    </div>
</div>
@endsection
