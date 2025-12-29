<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BankReconciliation extends Model
{
    protected $fillable = [
        'user_id',
        'account_id',
        'transaction_id',
        'statement_date',
        'statement_amount',
        'statement_description',
        'status',
        'reconciled_at',
        'notes',
    ];

    protected $casts = [
        'statement_amount' => 'decimal:2',
        'statement_date' => 'date',
        'reconciled_at' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }
}
