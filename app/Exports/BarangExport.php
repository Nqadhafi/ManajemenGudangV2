<?php

namespace App\Exports;

use App\Models\Barang;
use Maatwebsite\Excel\Concerns\{FromCollection, WithHeadings, WithMapping, ShouldAutoSize};

class BarangExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    private int $row = 0;

    public function collection()
    {
        return Barang::with(['kategori','supplier'])
            ->orderBy('nama_barang')
            ->get();
    }

    public function headings(): array
    {
        return [
            'no',
            'nama_barang',
            'satuan',
            'stok',
            'stok_minimum',
            'kategori_nama',
            'supplier_nama',
        ];
    }

    public function map($b): array
    {
        return [
            ++$this->row,
            $b->nama_barang,
            $b->satuan,
            (int) $b->stok,
            (int) $b->stok_minimum,
            optional($b->kategori)->nama,
            optional($b->supplier)->nama_supplier,
        ];
    }
}
