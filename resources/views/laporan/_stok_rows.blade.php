@php $no = 1; @endphp
@foreach($barangs as $barang)
  @php
    $hargaAvg  = $barang->avg_harga; // nullable
    $nilaiAset = is_null($hargaAvg) ? null : ($hargaAvg * $barang->stok);
  @endphp
  <tr>
    <td>{{ $no++ }}</td>
    <td>{{ $barang->nama_barang }}</td>
    <td>{{ $barang->kategori->nama ?? '-' }}</td>
    <td>{{ $barang->supplier->nama_supplier ?? '-' }}</td>
    <td>{{ $barang->stok }}</td>
    <td>{{ $barang->satuan }}</td>
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
    <td colspan="8" class="text-center">Tidak ada data barang</td>
  </tr>
@endif
