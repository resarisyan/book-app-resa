<?php

namespace App\Imports;

use App\Models\Book;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class BooksImport implements ToModel, WithHeadingRow, WithValidation, SkipsEmptyRows
{
    use Importable;
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {

        return new Book([
            'judul' => $row['judul'],
            'penulis' => $row['penulis'],
            'tahun' => $row['tahun'],
            'penerbit' => $row['penerbit'],
        ]);
    }

    public function rules(): array
    {
        return [
            'judul' => 'required|max:255|unique:books',
            'penulis' => 'required',
            'tahun' => 'required',
            'penerbit' => 'required',
        ];
    }
}
