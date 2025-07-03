<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'book_id',
        'loan_date',
        'due_date',
        'return_date',
        'status',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'loan_date' => 'date',
        'due_date' => 'date',
        'return_date' => 'date',
    ];

    /**
     * Get the user that owns the loan.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the book that was loaned.
     */
    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * Check if loan is overdue.
     */
    public function isOverdue()
    {
        return $this->status === 'active' && $this->due_date->isPast();
    }

    /**
     * Check if loan is active.
     */
    public function isActive()
    {
        return $this->status === 'active';
    }

    /**
     * Check if loan is returned.
     */
    public function isReturned()
    {
        return $this->status === 'returned';
    }

    /**
     * Scope to get active loans.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope to get overdue loans.
     */
    public function scopeOverdue($query)
    {
        return $query->where('status', 'active')
                    ->where('due_date', '<', now());
    }

    /**
     * Scope to get returned loans.
     */
    public function scopeReturned($query)
    {
        return $query->where('status', 'returned');
    }
} 