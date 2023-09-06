<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['email', 'message', 'created_at', 'replied'];
}
