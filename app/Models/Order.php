<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'sum',
        'gender',
        'hobby',
        'user_id',
        'celebration_id',
        'apartment',
        'floor',
        'intercom',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function celebration()
    {
        return $this->belongsTo(Celebration::class);
    }
}
