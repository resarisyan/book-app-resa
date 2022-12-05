<?php

namespace App\Http\Controllers;

use App\Exports\BooksExport;
use App\Imports\BooksImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\BookRequest;
use App\Http\Requests\ExcelRequest;

use App\Models\Book;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        return view('home', compact('user'));
    }

    public function books()
    {
        $user = Auth::user();
        $books = Book::all();
        return view('book', compact('user', 'books'));
    }

    public function submit_book(BookRequest $request)
    {
        $book = new Book;
        $book->judul = $request->get('judul');
        $book->penulis = $request->get('penulis');
        $book->tahun = $request->get('tahun');
        $book->penerbit = $request->get('penerbit');
        if ($request->hasFile('cover')) {
            $extension = $request->file('cover')->extension();
            $filename = 'cover_buku_' . time() . '.' . $extension;
            $request->file('cover')->storeAs('public/cover_buku', $filename);
            $book->cover = $filename;
        }
        $book->save();
        $notification = [
            'message' => 'Data Buku Berhasil Ditambahkan',
            'alert-type' => 'success'
        ];

        return redirect()->route('admin.books')->with($notification);
    }

    public function getDataBuku($id)
    {
        $buku = Book::find($id);
        return response()->json($buku);
    }

    public function update_book(BookRequest $request)
    {
        $book = Book::find($request->get('id'));

        $book->judul = $request->get('judul');
        $book->penulis = $request->get('penulis');
        $book->tahun = $request->get('tahun');
        $book->penerbit = $request->get('penerbit');

        if ($request->hasFile('cover')) {
            $extension = $request->file('cover')->extension();
            $filename = 'cover_buku_' . time() . '.' . $extension;
            $request->file('cover')->storeAs('public/cover_buku', $filename);
            Storage::delete('public/cover_buku/' . $request->get('old_cover'));
            $book->cover = $filename;
        }
        $book->save();
        $notification = [
            'message' => 'Data Buku Berhasil Diubah',
            'alert-type' => 'success'
        ];
        return redirect()->route('admin.books')->with($notification);
    }

    public function delete_book($id)
    {
        $book = Book::find($id);
        Storage::delete('public/cover_buku/' . $book->cover);
        $book->delete();
        $success = true;
        $message = 'Data Buku Berhasil Dihapus';
        return response()->json([
            'success' => $success,
            'message' => $message
        ]);
    }

    public function print_books()
    {
        $books = Book::all();
        $pdf = PDF::loadview('print_books', ['books' => $books]);
        return $pdf->download('data_buku.pdf');
    }

    public function export()
    {
        return Excel::download(new BooksExport, 'books.xlsx');
    }

    public function import(ExcelRequest $request)
    {
        Excel::import(new BooksImport, $request->file('file'));
        $notification = [
            'message' => 'Import Data Berhasil Dilakukan',
            'alert-type' => 'success'
        ];

        return redirect()->route('admin.books')->with($notification);
    }
}
