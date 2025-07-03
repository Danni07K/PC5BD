<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Show the dashboard.
     */
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();

        if ($user->isBibliotecario()) {
            // Statistics for bibliotecario
            $totalBooks = Book::count();
            $availableBooks = Book::available()->count();
            $totalLoans = Loan::count();
            $activeLoans = Loan::active()->count();
            $overdueLoans = Loan::overdue()->count();
            $totalUsers = User::where('role', 'usuario')->count();

            // Recent loans
            $recentLoans = Loan::with(['user', 'book'])
                ->latest()
                ->limit(5)
                ->get();

            // Most borrowed books
            $mostBorrowed = Book::withCount('loans')
                ->orderBy('loans_count', 'desc')
                ->limit(5)
                ->get();

            return view('dashboard.bibliotecario', compact(
                'totalBooks',
                'availableBooks',
                'totalLoans',
                'activeLoans',
                'overdueLoans',
                'totalUsers',
                'recentLoans',
                'mostBorrowed'
            ));
        } else {
            // Statistics for regular user
            $userLoans = $user->loans()->count();
            $activeUserLoans = $user->loans()->active()->count();
            $overdueUserLoans = $user->loans()->overdue()->count();

            // User's recent loans
            $recentUserLoans = $user->loans()
                ->with('book')
                ->latest()
                ->limit(5)
                ->get();

            // Available books
            $availableBooks = Book::available()->limit(5)->get();

            return view('dashboard.usuario', compact(
                'userLoans',
                'activeUserLoans',
                'overdueUserLoans',
                'recentUserLoans',
                'availableBooks'
            ));
        }
    }
}
