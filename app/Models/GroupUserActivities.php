<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupUserActivities extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function expense() {
        return $this->belongsTo(GroupExpenses::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function repayments() {
        return $this->hasMany(Repayments::class);
    }
}
