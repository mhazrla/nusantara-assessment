<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookRequest;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10); // Number of items per page
        $books = Book::latest()->get();
        $items = Book::paginate($perPage);

        return response()->json(['books' => $items], 200);
    }

    public function store(StoreBookRequest $request)
    {
        $validator = $request->validated();

        if (!Auth::user()) {
            return abort(401, 'Unauthorized');
        }

        $book = Book::create(
            $validator,
            [
                'user_id' => Auth::user()->id,
            ]
        );

        return response()->json([
            'message' => 'Book successfully registered',
            'book' => $book
        ], 200);
    }

    private function getBookId($id)
    {
        $book = Book::find($id);

        if (!$book) {
            abort(404, 'Buku tidak ditemukan');
        }

        return $book;
    }

    public function show($id)
    {
        $book = $this->getBookId($id);
        return response()->json(['book' => $book], 200);
    }

    public function update(StoreBookRequest $request, Book $book)
    {
        $book = $this->getBookId($book->id);

        if (!Auth::user()) {
            return abort(401, 'Unauthorized');
        }

        if (Auth::user()->id != $book->user_id) {
            abort(403, "User doesn't have right");
        }

        $validator = $request->validated();

        $book->isbn = $request->input('isbn');
        $book->title = $request->input('title');
        $book->subtitle = $request->input('subtitle');
        $book->author = $request->input('author');
        $book->published = $request->input('published');
        $book->publisher = $request->input('publisher');
        $book->pages = $request->input('pages');
        $book->description = $request->input('description');
        $book->website = $request->input('website');

        $book->save();

        return response()->json([
            'message' => 'Buku berhasil diperbarui',
            'book' => $book
        ], 202);
    }

    public function destroy(Book $book)
    {
        $book = $this->getBookId($book->id);

        if (!Auth::user()) {
            return abort(401, 'Unauthorized');
        }

        if (Auth::user()->id != $book->user_id) {
            abort(403, "User doesn't have right");
        }

        $book->delete();

        return response()->json([
            'message' => 'Buku berhasil dihapus',
        ], 204);
    }
}
