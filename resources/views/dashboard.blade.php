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
</div>
@endsection