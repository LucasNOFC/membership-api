<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{

    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'duration_days',
        'is_active',
        'description',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function members() : HasMany 
    {
        return $this->hasMany(Member::class);
    }
}
