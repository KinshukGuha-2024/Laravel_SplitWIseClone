<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Repayments extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function paidBy() {
        return $this->belongsTo(User::class, 'paid_by');
    }

    public function paidTo() {
        return $this->belongsTo(User::class, 'paid_to');
    }


}
