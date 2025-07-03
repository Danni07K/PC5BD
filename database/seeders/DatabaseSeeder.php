<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Loan;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create bibliotecario user
        $bibliotecario = User::create([
            'name' => 'Admin Bibliotecario',
            'email' => 'admin@biblioteca.com',
            'password' => Hash::make('password'),
            'role' => 'bibliotecario',
        ]);

        // Create regular users
        $user1 = User::create([
            'name' => 'Juan Pérez',
            'email' => 'juan@example.com',
            'password' => Hash::make('password'),
            'role' => 'usuario',
        ]);

        $user2 = User::create([
            'name' => 'María García',
            'email' => 'maria@example.com',
            'password' => Hash::make('password'),
            'role' => 'usuario',
        ]);

        // Create books
        $books = [
            [
                'title' => 'El Quijote',
                'author' => 'Miguel de Cervantes',
                'isbn' => '9788491051185',
                'description' => 'Obra maestra de la literatura española',
                'category' => 'Clásico',
                'quantity' => 5,
                'available_quantity' => 5,
                'location' => 'Estante A1',
            ],
            [
                'title' => 'Cien años de soledad',
                'author' => 'Gabriel García Márquez',
                'isbn' => '9788497592208',
                'description' => 'Realismo mágico latinoamericano',
                'category' => 'Literatura',
                'quantity' => 3,
                'available_quantity' => 3,
                'location' => 'Estante B2',
            ],
            [
                'title' => 'Don Juan Tenorio',
                'author' => 'José Zorrilla',
                'isbn' => '9788420733745',
                'description' => 'Drama romántico español',
                'category' => 'Teatro',
                'quantity' => 2,
                'available_quantity' => 2,
                'location' => 'Estante C3',
            ],
            [
                'title' => 'La Divina Comedia',
                'author' => 'Dante Alighieri',
                'isbn' => '9788420733746',
                'description' => 'Poema épico medieval',
                'category' => 'Clásico',
                'quantity' => 4,
                'available_quantity' => 4,
                'location' => 'Estante A2',
            ],
            [
                'title' => 'Romeo y Julieta',
                'author' => 'William Shakespeare',
                'isbn' => '9788420733747',
                'description' => 'Tragedia romántica',
                'category' => 'Teatro',
                'quantity' => 3,
                'available_quantity' => 3,
                'location' => 'Estante C4',
            ],
        ];

        foreach ($books as $bookData) {
            Book::create($bookData);
        }

        // Create some loans
        $book1 = Book::where('isbn', '9788491051185')->first();
        $book2 = Book::where('isbn', '9788497592208')->first();

        // Active loan
        Loan::create([
            'user_id' => $user1->id,
            'book_id' => $book1->id,
            'loan_date' => now()->subDays(5),
            'due_date' => now()->addDays(10),
            'status' => 'active',
            'notes' => 'Préstamo regular',
        ]);

        // Overdue loan
        Loan::create([
            'user_id' => $user2->id,
            'book_id' => $book2->id,
            'loan_date' => now()->subDays(20),
            'due_date' => now()->subDays(5),
            'status' => 'overdue',
            'notes' => 'Préstamo vencido',
        ]);

        // Returned loan
        Loan::create([
            'user_id' => $user1->id,
            'book_id' => $book2->id,
            'loan_date' => now()->subDays(30),
            'due_date' => now()->subDays(15),
            'return_date' => now()->subDays(10),
            'status' => 'returned',
            'notes' => 'Libro devuelto a tiempo',
        ]);

        // Update book availability
        $book1->decrement('available_quantity');
        $book2->decrement('available_quantity');

        $this->command->info('✅ Database seeded successfully!');
        $this->command->info('📚 Created ' . count($books) . ' books');
        $this->command->info('👥 Created 3 users (1 bibliotecario, 2 usuarios)');
        $this->command->info('📖 Created 3 loans (1 active, 1 overdue, 1 returned)');
        $this->command->info('');
        $this->command->info('🔑 Login credentials:');
        $this->command->info('   Bibliotecario: admin@biblioteca.com / password');
        $this->command->info('   Usuario: juan@example.com / password');
        $this->command->info('   Usuario: maria@example.com / password');
    }
}
