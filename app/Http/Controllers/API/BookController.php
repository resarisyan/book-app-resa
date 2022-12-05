<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;
use Exception;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\BookRequest;

class BookController extends Controller
{
    public function books()
    {
        try {
            $books = Book::all();
            return response()->json([
                'message' => 'success',
                'books' => $books
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Request failed'
            ], 401);
        }
    }

    public function create(BookRequest $request)
    {

        if ($request->hasFile('cover')) {
            $extension = $request->file('cover')->extension();
            $filename = 'cover_buku_' . time() . '.' . $extension;
            $request->file('cover')->storeAs(
                'public/cover_buku',
                $filename
            );
            $validated['cover'] = $filename;
        }

        Book::create($validated);

        return response()->json([
            'message' => 'Buku Berhasil Ditambahkan',
            'book' => $validated,
        ], 200);
    }

    public function update(BookRequest $request, $id)
    {

        if ($request->hasFile('cover')) {
            $extension = $request->file('cover')->extension();
            $filename = 'cover_buku_' . time() . '.' . $extension;
            $request->file('cover')->storeAs(
                'public/cover_buku',
                $filename
            );
            $validated['cover'] = $filename;
        }

        $book = Book::find($id);
        Storage::delete('public/cover_buku' . $book->cover);
        $book->update($validated);

        return response()->json([
            'message' => 'Buku Berhasil Diubah',
            'book' => $book
        ], 200);
    }

    public function delete($id)
    {
        $book = Book::find($id);
        Storage::delete('public/cover_buku' . $book->cover);
        $book->delete();
        return response()->json([
            'message' => 'Buku Berhasil Dihapus'
        ], 200);
    }
}
