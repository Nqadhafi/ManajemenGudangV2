@foreach($barangs as $barang)
<tr>
  <td>{{ $loop->iteration }}</td>
  <td>{{ $barang->nama_barang }}</td>
  <td>{{ $barang->kategori->nama ?? '-' }}</td>
  <td>{{ $barang->supplier->nama_supplier ?? '-' }}</td>
  <td>{{ $barang->stok }}</td>
  <td>{{ $barang->satuan }}</td>
  <td>{{ $barang->stok_minimum }}</td>
  <td>
    @if($barang->stok <= 0)
      <span class="badge badge-danger">Habis</span>
    @elseif($barang->stok <= ($barang->stok_minimum * 1))
      <span class="badge badge-warning">Menipis</span>
    @else
      <span class="badge badge-success">Aman</span>
    @endif
  </td>
  <td class="text-nowrap">
    <a href="{{ route('barangs.kartu-stok', $barang->id) }}" class="btn btn-info btn-sm" title="Kartu Stok">
      <i class="fas fa-list"></i>
    </a>
    <a href="{{ route('barangs.edit', $barang->id) }}" class="btn btn-warning btn-sm">
      <i class="fas fa-edit"></i>
    </a>
    <form action="{{ route('barangs.destroy', $barang->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin hapus barang ini?')">
      @csrf
      @method('DELETE')
      <button type="submit" class="btn btn-danger btn-sm">
        <i class="fas fa-trash"></i>
      </button>
    </form>
  </td>
</tr>
@endforeach

@if($barangs->isEmpty())
<tr>
  <td colspan="9" class="text-center">Tidak ada data</td>
</tr>
@endif
