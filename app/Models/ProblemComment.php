<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProblemComment extends Model
{
    use HasFactory;
    protected $fillable = ['comment', 'resolved_by',  'created_at'];

    public function problem()
    {
        return $this->belongsTo(OrderProblem::class);
    }
}
