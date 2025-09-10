<?php

namespace App\Exports;

use App\Models\Kategori;
use Maatwebsite\Excel\Concerns\{FromCollection, WithHeadings, WithMapping, ShouldAutoSize};

class KategoriExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    private int $row = 0;

    public function collection()
    {
        return Kategori::orderBy('nama')->get();
    }

    public function headings(): array
    {
        return ['no','nama'];
    }

    public function map($k): array
    {
        return [++$this->row, $k->nama];
    }
}
