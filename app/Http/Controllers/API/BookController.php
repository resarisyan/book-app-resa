<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;
use Exception;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\BookRequest;
use Illuminate\Support\Facades\Validator;


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

    public function create(Request $request)
    {
        $validated = Validator::make($request->all(),[
            'judul' => 'required|max:255|unique:books',
            'penulis' => 'required',
            'tahun' => 'required',
            'penerbit' => 'required',
            'cover' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validated->fails()){
            return response()->json([
                'message' => 'Buku Gagal Ditambahkan',
                'book' => $validated->errors()->first()
            ], 422);
        }

        $data = [
            'judul' => $request->get('judul'),
            'penulis' => $request->get('penulis'),
            'tahun' => $request->get('tahun'),
            'penerbit' => $request->get('penerbit'),
        ];

        if ($request->hasFile('cover')) {
            $extension = $request->file('cover')->extension();
            $filename = 'cover_buku_' . time() . '.' . $extension;
            $request->file('cover')->storeAs(
                'public/cover_buku',
                $filename
            );
            $data['cover'] = $filename;
        }

        $book = Book::create($data);

        if($book){
            return response()->json([
                'message' => 'Buku Berhasil Ditambahkan',
                'book' => $book
            ], 200);
        } else {
            return response()->json([
                'message' => 'Buku Gagal Ditambahkan',
                'book' => $book
            ], 400);
        }
    }

    public function update(Request $request, $id)
    {
        $validated = Validator::make($request->all(),[
            'judul' => 'required|max:255|unique:books',
            'penulis' => 'required',
            'tahun' => 'required',
            'penerbit' => 'required',
            'cover' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validated->fails()){
            return response()->json([
                'message' => 'Buku Gagal Ditambahkan',
                'book' => $validated->errors()->first()
            ], 422);
        }

        $data = [
            'judul' => $request->get('judul'),
            'penulis' => $request->get('penulis'),
            'tahun' => $request->get('tahun'),
            'penerbit' => $request->get('penerbit'),
        ];

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
        $book->update($data);

        if($book){
            return response()->json([
                'message' => 'Buku Berhasil Ditambahkan',
                'book' => $book
            ], 200);
        } else {
            return response()->json([
                'message' => 'Buku Gagal Ditambahkan',
                'book' => $book
            ], 400);
        }
        
    }

    public function delete($id)
    {
        $book = Book::find($id);
        if($book){
            Storage::delete('public/cover_buku' . $book->cover);
            $book->delete();
            return response()->json([
                'message' => 'Buku Berhasil Dihapus'
            ], 200);
        } else {
            return response()->json([
                'message' => 'ID Buku Tidak Ditemukan',
            ], 400);
        }
    }
}
