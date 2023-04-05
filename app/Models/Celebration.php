<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Celebration extends Model
{
    use HasFactory;

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public static function getIdByName(string $name): int
    {
        $celebration = self::where('name', $name)->first();
        return $celebration ? $celebration->id : 0;
    }
}
