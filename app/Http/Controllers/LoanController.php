<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Loan;
use App\Models\User;
use App\Services\OracleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class LoanController extends Controller
{
    /**
     * Display a listing of loans.
     */
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();

        if ($user->isBibliotecario()) {
            $loans = Loan::with(['user', 'book'])->latest()->paginate(10);
        } else {
            $loans = $user->loans()->with('book')->latest()->paginate(10);
        }

        return view('loans.index', compact('loans'));
    }

    /**
     * Show the form for creating a new loan.
     */
    public function create()
    {
        $books = Book::available()->get();
        $users = User::where('role', 'usuario')->get();

        return view('loans.create', compact('books', 'users'));
    }

    /**
     * Store a newly created loan using PL/SQL procedure.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'book_id' => 'required|exists:books,id',
            'due_date' => 'required|date|after:today',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Call Oracle service to create loan
            $result = OracleService::createLoan(
                $request->user_id,
                $request->book_id,
                $request->due_date,
                $request->notes
            );

            if ($result['success']) {
                return redirect()->route('loans.index')
                    ->with('success', 'Préstamo registrado exitosamente.');
            } else {
                return redirect()->back()
                    ->withErrors(['error' => $result['message']])
                    ->withInput();
            }
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Error al registrar el préstamo: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Show the form for returning a book.
     */
    public function returnForm($id)
    {
        $loan = Loan::with(['user', 'book'])->findOrFail($id);

        return view('loans.return', compact('loan'));
    }

    /**
     * Return a book using PL/SQL procedure.
     */
    public function returnBook(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'return_date' => 'required|date|before_or_equal:today',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Call Oracle service to return book
            $result = OracleService::returnBook(
                $id,
                $request->return_date,
                $request->notes
            );

            if ($result['success']) {
                return redirect()->route('loans.index')
                    ->with('success', 'Libro devuelto exitosamente.');
            } else {
                return redirect()->back()
                    ->withErrors(['error' => $result['message']])
                    ->withInput();
            }
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Error al devolver el libro: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Show loan history.
     */
    public function history()
    {
        /** @var User $user */
        $user = Auth::user();

        if ($user->isBibliotecario()) {
            $loans = Loan::with(['user', 'book'])->latest()->get();
        } else {
            $loans = $user->loans()->with('book')->latest()->get();
        }

        return view('loans.history', compact('loans'));
    }

    /**
     * Show overdue loans report.
     */
    public function overdue()
    {
        $overdueLoans = OracleService::getOverdueLoans();

        return view('loans.overdue', compact('overdueLoans'));
    }

    /**
     * Show most borrowed books report.
     */
    public function mostBorrowed()
    {
        $mostBorrowed = OracleService::getMostBorrowedBooks(10);

        return view('loans.most-borrowed', compact('mostBorrowed'));
    }
}
