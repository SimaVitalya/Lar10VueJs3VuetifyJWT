<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    protected $guarded = false;
    protected $table = 'replies';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');    }

    public function comment()
    {
        return $this->belongsTo(Comments::class, 'comment_id');
    }
}
