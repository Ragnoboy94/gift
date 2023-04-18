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
        'status_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function celebration()
    {
        return $this->belongsTo(Celebration::class);
    }

    public function status()
    {
        return $this->belongsTo(OrderStatus::class, 'status_id');
    }
}
