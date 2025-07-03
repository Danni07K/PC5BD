<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OracleService
{

    public static function createLoan($userId, $bookId, $dueDate, $notes = null)
    {
        try {
            $pdo = DB::connection('oracle')->getPdo();
            $stmt = $pdo->prepare("
                BEGIN
                    loan_package.create_loan(
                        :user_id,
                        :book_id,
                        TO_DATE(:due_date, 'YYYY-MM-DD'),
                        :notes,
                        :success,
                        :message
                    );
                END;
            ");
            $success = 0;
            $message = str_repeat(' ', 4000);
            $stmt->bindParam(':user_id', $userId);
            $stmt->bindParam(':book_id', $bookId);
            $stmt->bindParam(':due_date', $dueDate);
            $stmt->bindParam(':notes', $notes);
            $stmt->bindParam(':success', $success, \PDO::PARAM_INPUT_OUTPUT, 1);
            $stmt->bindParam(':message', $message, \PDO::PARAM_INPUT_OUTPUT, 4000);
            $stmt->execute();
            return [
                'success' => (bool) $success,
                'message' => trim($message)
            ];
        } catch (\Exception $e) {
            Log::error('Oracle createLoan error: ' . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }


    public static function returnBook($loanId, $returnDate, $notes = null)
    {
        try {
            $pdo = DB::connection('oracle')->getPdo();
            $stmt = $pdo->prepare("
                BEGIN
                    loan_package.return_book(
                        :loan_id,
                        TO_DATE(:return_date, 'YYYY-MM-DD'),
                        :notes,
                        :success,
                        :message
                    );
                END;
            ");
            $success = 0;
            $message = str_repeat(' ', 4000);
            $stmt->bindParam(':loan_id', $loanId);
            $stmt->bindParam(':return_date', $returnDate);
            $stmt->bindParam(':notes', $notes);
            $stmt->bindParam(':success', $success, \PDO::PARAM_INPUT_OUTPUT, 1);
            $stmt->bindParam(':message', $message, \PDO::PARAM_INPUT_OUTPUT, 4000);
            $stmt->execute();
            return [
                'success' => (bool) $success,
                'message' => trim($message)
            ];
        } catch (\Exception $e) {
            Log::error('Oracle returnBook error: ' . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }


    public static function getOverdueLoans()
    {
        try {
            $pdo = DB::connection('oracle')->getPdo();
            $stmt = $pdo->query('SELECT * FROM v_overdue_loans');
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            Log::error('Oracle getOverdueLoans error: ' . $e->getMessage());
            return [];
        }
    }


    public static function getMostBorrowedBooks($limit = 10)
    {
        try {
            $pdo = DB::connection('oracle')->getPdo();
            $stmt = $pdo->query('SELECT * FROM v_most_borrowed_books WHERE ROWNUM <= ' . (int)$limit);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            Log::error('Oracle getMostBorrowedBooks error: ' . $e->getMessage());
            return [];
        }
    }


    private static function createLoanSQLite($userId, $bookId, $dueDate, $notes = null)
    {
        DB::beginTransaction();

        try {
            $book = DB::table('books')->where('id', $bookId)->first();

            if (!$book || $book->available_quantity <= 0) {
                return ['success' => false, 'message' => 'El libro no está disponible para préstamo.'];
            }

            $loanId = DB::table('loans')->insertGetId([
                'user_id' => $userId,
                'book_id' => $bookId,
                'loan_date' => now(),
                'due_date' => $dueDate,
                'status' => 'active',
                'notes' => $notes,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('books')
                ->where('id', $bookId)
                ->decrement('available_quantity');

            DB::commit();

            return ['success' => true, 'message' => 'Préstamo creado exitosamente con ID: ' . $loanId];
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }


    private static function returnBookSQLite($loanId, $returnDate, $notes = null)
    {
        DB::beginTransaction();

        try {
            $loan = DB::table('loans')->where('id', $loanId)->first();

            if (!$loan || $loan->status !== 'active') {
                return ['success' => false, 'message' => 'El préstamo no existe o ya fue devuelto.'];
            }

            DB::table('loans')
                ->where('id', $loanId)
                ->update([
                    'return_date' => $returnDate,
                    'status' => 'returned',
                    'notes' => $notes,
                    'updated_at' => now(),
                ]);

            DB::table('books')
                ->where('id', $loan->book_id)
                ->increment('available_quantity');

            DB::commit();

            return ['success' => true, 'message' => 'Libro devuelto exitosamente.'];
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
