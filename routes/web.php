<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoanController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckRole;
// Public routes
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Protected routes
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Books routes
    Route::resource('books', BookController::class);
    Route::get('/books-available', [BookController::class, 'available'])->name('books.available');

    // Loans routes
    Route::resource('loans', LoanController::class)->except(['edit', 'update', 'destroy']);
    Route::get('/loans/{loan}/return', [LoanController::class, 'returnForm'])->name('loans.return-form');
    Route::post('/loans/{loan}/return', [LoanController::class, 'returnBook'])->name('loans.return');
    Route::get('/loans-history', [LoanController::class, 'history'])->name('loans.history');

    // Reports (only for bibliotecario)
    Route::middleware([CheckRole::class . ':bibliotecario'])->group(function () {
        Route::get('/reports/overdue', [LoanController::class, 'overdue'])->name('reports.overdue');
        Route::get('/reports/most-borrowed', [LoanController::class, 'mostBorrowed'])->name('reports.most-borrowed');
    });

    // Ruta temporal para depuración del rol del usuario autenticado
    Route::get('/debug-role', function () {
        $user = \Illuminate\Support\Facades\Auth::user();
        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
        ]);
    });
});
