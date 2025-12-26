<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Groups extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function groupUsers() {
        return $this->hasMany(GroupUsers::class);
    }

    public function expenses()
    {
        return $this->belongsToMany(GroupExpenses::class);
    }

    public function repayments() {
        return $this->hasMany(Repayments::class);
    }
}
