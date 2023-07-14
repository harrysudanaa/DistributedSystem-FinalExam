<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class Expenditure extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'book_name', 'quantity', 'price', 'total_price', 'supplier', 'date', 'user_id'
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
