<?php

namespace App\Imports;

use App\Models\Kategori;
use Maatwebsite\Excel\Concerns\{ToModel, WithHeadingRow, WithValidation, SkipsOnFailure, SkipsFailures};

class KategoriImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    protected bool $caseSensitive;

    public function __construct(bool $caseSensitive = false)
    {
        $this->caseSensitive = $caseSensitive;
    }

    public function model(array $row)
    {
        $nama = trim((string)($row['nama'] ?? ''));
        if ($nama === '') return null;

        if ($this->caseSensitive) {
            $existing = Kategori::whereRaw('BINARY nama = ?', [$nama])->first();
        } else {
            $existing = Kategori::whereRaw('LOWER(nama) = ?', [mb_strtolower($nama)])->first();
        }

        if ($existing) {
            // update name ke bentuk terbaru (opsional)
            $existing->nama = $nama;
            $existing->save();
            return null;
        }

        return new Kategori(['nama' => $nama]);
    }

    public function rules(): array
    {
        return ['nama' => ['required','string']];
    }
}
