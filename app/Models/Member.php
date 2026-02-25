<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Member extends Model
{

    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'plan_id',
        'due_day',
        'status',
    ];

    public function plan() : BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function payments() : HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
