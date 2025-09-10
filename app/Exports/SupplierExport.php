<?php

namespace App\Exports;

use App\Models\Supplier;
use Maatwebsite\Excel\Concerns\{FromCollection, WithHeadings, WithMapping, ShouldAutoSize};

class SupplierExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    private int $row = 0;

    public function collection()
    {
        return Supplier::orderBy('nama_supplier')->get();
    }

    public function headings(): array
    {
        return ['no','nama_supplier','no_hp_supplier','alamat'];
    }

    public function map($s): array
    {
        return [++$this->row, $s->nama_supplier, $s->no_hp_supplier, $s->alamat];
    }
}
