<?php

namespace App\Imports;

use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\{
    ToModel, WithHeadingRow, SkipsOnFailure, SkipsFailures, WithValidation
};

class BarangImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    protected bool $autoCreate;
    protected bool $caseSensitive;

    // Untuk deteksi duplikat dalam file (natural key)
    protected array $seenKeys = [];

    public function __construct(bool $autoCreate = false, bool $caseSensitive = false)
    {
        $this->autoCreate    = $autoCreate;
        $this->caseSensitive = $caseSensitive;
    }

    public function model(array $row)
    {
        $namaBarang   = trim((string)($row['nama_barang'] ?? ''));
        $satuan       = trim((string)($row['satuan'] ?? ''));
        $stok         = (int)($row['stok'] ?? 0);
        $stokMinimum  = (int)($row['stok_minimum'] ?? 0);
        $kategoriNama = trim((string)($row['kategori_nama'] ?? ''));
        $supplierNama = trim((string)($row['supplier_nama'] ?? ''));

        // 1) Validasi wajib ada di sini juga biar aman dari null (selain rules)
        if ($namaBarang === '' || $satuan === '' || $kategoriNama === '' || $supplierNama === '') {
            return null; // akan tercapture di layer validation juga
        }

        // 2) Case handling helper
        $eq = fn($col, $val) =>
            $this->caseSensitive
              ? [ "BINARY {$col} = ?", [$val] ]
              : [ "LOWER({$col}) = ?", [mb_strtolower($val)] ];

        // 3) Resolve kategori
        [$sqlK, $bindK] = $eq('nama', $kategoriNama);
        $kategori = Kategori::whereRaw($sqlK, $bindK)->first();
        if (!$kategori && $this->autoCreate) {
            $kategori = Kategori::create(['nama' => $kategoriNama]);
        }
        if (!$kategori) return null; // tidak ditemukan & tidak auto-create → failure via rules below

        // 4) Resolve supplier
        [$sqlS, $bindS] = $eq('nama_supplier', $supplierNama);
        $supplier = Supplier::whereRaw($sqlS, $bindS)->first();
        if (!$supplier && $this->autoCreate) {
            $supplier = Supplier::create(['nama_supplier' => $supplierNama]);
        }
        if (!$supplier) return null;

        // 5) Cegah duplikat dalam file
        $key = ($this->caseSensitive)
            ? "{$namaBarang}|{$kategoriNama}|{$supplierNama}"
            : mb_strtolower("{$namaBarang}|{$kategoriNama}|{$supplierNama}");
        if (isset($this->seenKeys[$key])) {
            // duplikat baris di file → abaikan baris kedua (atau bisa push failure custom)
            return null;
        }
        $this->seenKeys[$key] = true;

        // 6) Upsert by natural key di DB
        return DB::transaction(function () use ($namaBarang, $satuan, $stok, $stokMinimum, $kategori, $supplier, $eq) {

            // Cari existing barang by nama + kategori + supplier (respect case option)
            [$sqlB, $bindB] = $this->caseSensitive
                ? [ 'BINARY nama_barang = ?', [$namaBarang] ]
                : [ 'LOWER(nama_barang) = ?', [mb_strtolower($namaBarang)] ];

            $existing = Barang::whereRaw($sqlB, $bindB)
                ->where('id_kategori', $kategori->id)
                ->where('id_supplier', $supplier->id)
                ->first();

            $data = [
                'nama_barang'  => $namaBarang,
                'satuan'       => $satuan,
                'stok'         => $stok,
                'stok_minimum' => $stokMinimum,
                'id_kategori'  => $kategori->id,
                'id_supplier'  => $supplier->id,
            ];

            if ($existing) {
                $existing->fill($data)->save();
                return null;
            }

            return new Barang($data);
        });
    }

    public function rules(): array
    {
        return [
            'nama_barang'   => ['required','string'],
            'satuan'        => ['required','string'],
            'stok'          => ['nullable','integer','min:0'],
            'stok_minimum'  => ['required','integer','min:0'],
            'kategori_nama' => ['required','string'],
            'supplier_nama' => ['required','string'],
        ];
    }
}
