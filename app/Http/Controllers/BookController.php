<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{
    /**
     * Display a listing of books.
     */
    public function index(Request $request)
    {
        $query = Book::query();
        
        if ($request->has('search')) {
            $query->search($request->search);
        }
        
        if ($request->has('category')) {
            $query->where('category', $request->category);
        }
        
        $books = $query->paginate(12);
        $categories = Book::distinct()->pluck('category');
        
        return view('books.index', compact('books', 'categories'));
    }

    /**
     * Show the form for creating a new book.
     */
    public function create()
    {
        return view('books.create');
    }

    /**
     * Store a newly created book.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'required|string|unique:books,isbn|max:13',
            'description' => 'nullable|string',
            'category' => 'required|string|max:100',
            'quantity' => 'required|integer|min:1',
            'location' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $book = Book::create([
            'title' => $request->title,
            'author' => $request->author,
            'isbn' => $request->isbn,
            'description' => $request->description,
            'category' => $request->category,
            'quantity' => $request->quantity,
            'available_quantity' => $request->quantity,
            'location' => $request->location,
        ]);

        return redirect()->route('books.index')
            ->with('success', 'Libro agregado exitosamente.');
    }

    /**
     * Display the specified book.
     */
    public function show($id)
    {
        $book = Book::with('loans.user')->findOrFail($id);
        
        return view('books.show', compact('book'));
    }

    /**
     * Show the form for editing the specified book.
     */
    public function edit($id)
    {
        $book = Book::findOrFail($id);
        
        return view('books.edit', compact('book'));
    }

    /**
     * Update the specified book.
     */
    public function update(Request $request, $id)
    {
        $book = Book::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'required|string|max:13|unique:books,isbn,' . $id,
            'description' => 'nullable|string',
            'category' => 'required|string|max:100',
            'quantity' => 'required|integer|min:1',
            'location' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Calculate new available quantity
        $loaned = $book->quantity - $book->available_quantity;
        $newAvailable = max(0, $request->quantity - $loaned);

        $book->update([
            'title' => $request->title,
            'author' => $request->author,
            'isbn' => $request->isbn,
            'description' => $request->description,
            'category' => $request->category,
            'quantity' => $request->quantity,
            'available_quantity' => $newAvailable,
            'location' => $request->location,
        ]);

        return redirect()->route('books.index')
            ->with('success', 'Libro actualizado exitosamente.');
    }

    /**
     * Remove the specified book.
     */
    public function destroy($id)
    {
        $book = Book::findOrFail($id);
        
        // Check if book has active loans
        if ($book->activeLoans()->count() > 0) {
            return redirect()->back()
                ->withErrors(['error' => 'No se puede eliminar un libro que tiene préstamos activos.']);
        }
        
        $book->delete();
        
        return redirect()->route('books.index')
            ->with('success', 'Libro eliminado exitosamente.');
    }

    /**
     * Show available books.
     */
    public function available()
    {
        $books = Book::available()->paginate(12);
        
        return view('books.available', compact('books'));
    }
} 