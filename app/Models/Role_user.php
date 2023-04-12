<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role_user extends Model
{
    protected $fillable = [
        'rating',
        'user_id',
    ];
    use HasFactory;
    protected $table = 'role_user';
    public function users()
    {
        return $this->belongsTo(User::class);
    }
}
