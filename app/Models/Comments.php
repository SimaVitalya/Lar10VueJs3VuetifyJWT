<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comments extends Model
{
    protected $guarded = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
