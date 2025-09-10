<?php

namespace App\Imports;

use App\Models\Supplier;
use Maatwebsite\Excel\Concerns\{ToModel, WithHeadingRow, WithValidation, SkipsOnFailure, SkipsFailures};

class SupplierImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    protected bool $caseSensitive;

    public function __construct(bool $caseSensitive = false)
    {
        $this->caseSensitive = $caseSensitive;
    }

    public function model(array $row)
    {
        $nama    = trim((string)($row['nama_supplier'] ?? ''));
        $no_hp   = $row['no_hp_supplier'] ?? null;
        $alamat  = $row['alamat'] ?? null;

        if ($nama === '') return null;

        $existing = $this->caseSensitive
            ? Supplier::whereRaw('BINARY nama_supplier = ?', [$nama])->first()
            : Supplier::whereRaw('LOWER(nama_supplier) = ?', [mb_strtolower($nama)])->first();

        if ($existing) {
            $existing->fill(['nama_supplier'=>$nama, 'no_hp_supplier'=>$no_hp, 'alamat'=>$alamat])->save();
            return null;
        }

        return new Supplier(['nama_supplier'=>$nama, 'no_hp_supplier'=>$no_hp, 'alamat'=>$alamat]);
    }

    public function rules(): array
    {
        return ['nama_supplier' => ['required','string']];
    }
}
