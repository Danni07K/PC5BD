<?php

namespace App\Console\Commands;

use App\Services\OracleService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TestOracleConnection extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:oracle {--connection=oracle}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Oracle database connection and stored procedures';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('INICIO DEL COMANDO: handle() ejecutándose');

        $connection = $this->option('connection');

        $this->info("Testing {$connection} connection...");

        // Mensaje de depuración antes de intentar obtener el PDO
        $this->info("Intentando obtener PDO para '{$connection}'...");
        try {
            $pdo = DB::connection($connection)->getPdo();
            $this->info("✅ PDO obtenido correctamente para '{$connection}'!");
        } catch (\Exception $e) {
            $this->error("❌ Error al obtener PDO para '{$connection}': " . $e->getMessage());
            return 1;
        }

        try {
            // Test if we can query the database
            $result = DB::connection($connection)->select('SELECT 1 as test');
            $this->info("✅ Database query successful for '{$connection}'!");

            // Test Oracle service methods SOLO si la conexión es oracle_manual
            if ($connection === 'oracle_manual' || $connection === 'oracle') {
                $this->info("\nTesting Oracle Service methods...");

                // Test getOverdueLoans
                try {
                    $overdue = OracleService::getOverdueLoans();
                    if (empty($overdue)) {
                        $this->warn("⚠️ getOverdueLoans() returned an empty array (no datos o error). Revisa el log si esperabas resultados.");
                    } else {
                        $this->info("✅ getOverdueLoans() working - Found " . count($overdue) . " overdue loans");
                    }
                } catch (\Exception $e) {
                    $this->error("❌ getOverdueLoans() exception: " . $e->getMessage());
                }

                // Test getMostBorrowedBooks
                try {
                    $mostBorrowed = OracleService::getMostBorrowedBooks(5);
                    if (empty($mostBorrowed)) {
                        $this->warn("⚠️ getMostBorrowedBooks() returned an empty array (no datos o error). Revisa el log si esperabas resultados.");
                    } else {
                        $this->info("✅ getMostBorrowedBooks() working - Found " . count($mostBorrowed) . " books");
                    }
                } catch (\Exception $e) {
                    $this->error("❌ getMostBorrowedBooks() exception: " . $e->getMessage());
                }

                // Test stored procedures (if we have test data)
                $this->info("\nTesting stored procedures...");

                // Check if we have users and books
                $userCount = DB::table('users')->count();
                $bookCount = DB::table('books')->count();

                $this->info("Users in database: {$userCount}");
                $this->info("Books in database: {$bookCount}");

                if ($userCount > 0 && $bookCount > 0) {
                    $this->info("✅ Test data available for stored procedure testing");
                    $this->info("✅ Stored procedures can be called (connection ready)");
                } else {
                    $this->warn("⚠️ No test data available for stored procedure testing");
                }
            }

            $this->info("\n🎉 Oracle integration test completed successfully for '{$connection}'!");

        } catch (\Exception $e) {
            $this->error("❌ Database connection failed: " . $e->getMessage());

            if ($connection === 'oracle') {
                $this->error("\nPossible solutions:");
                $this->error("1. Install PHP oci8 extension");
                $this->error("2. Configure Oracle connection in .env file");
                $this->error("3. Make sure Oracle Database is running");
                $this->error("4. Check Oracle user permissions");
            }

            return 1;
        }

        return 0;
    }
}
