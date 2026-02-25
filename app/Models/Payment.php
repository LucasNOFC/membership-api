<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{


    use HasFactory;

    protected $fillable = [
        'member_id',
        'amount',
        'reference_month',
        'paid_at',
        'receipt_path',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'date',
    ];

    public function member() : BelongsTo
    {
        return $this->belongsTo(Member::class);
    }
    
}
