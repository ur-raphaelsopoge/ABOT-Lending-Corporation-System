<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoanApplication extends Model
{
    public const MIN_AMOUNT = 1000;

    public const MAX_AMOUNT = 50000;

    public const TERMS = [3, 6, 9, 12, 18, 24, 36];

    public const STATUSES = ['pending', 'approved', 'rejected'];

    protected $fillable = [
        'user_id',
        'loan_type',
        'amount',
        'term_months',
        'purpose',
        'status',
        'admin_remarks',
        'reviewed_by',
        'reviewed_at',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'reviewed_at' => 'datetime',
        ];
    }

    public function loanTypeLabel(): string
    {
        return User::LOAN_TYPES[$this->loan_type]['label'] ?? '—';
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'reviewed_by');
    }
}
