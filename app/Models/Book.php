<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'author',
        'isbn',
        'description',
        'category',
        'quantity',
        'available_quantity',
        'location',
    ];

    /**
     * Get the loans for the book.
     */
    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    /**
     * Check if book is available for loan.
     */
    public function isAvailable()
    {
        return $this->available_quantity > 0;
    }

    /**
     * Get active loans for this book.
     */
    public function activeLoans()
    {
        return $this->loans()->where('status', 'active');
    }

    /**
     * Scope to get available books.
     */
    public function scopeAvailable($query)
    {
        return $query->where('available_quantity', '>', 0);
    }

    /**
     * Scope to search books by title or author.
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('author', 'like', "%{$search}%")
              ->orWhere('isbn', 'like', "%{$search}%");
        });
    }
} 