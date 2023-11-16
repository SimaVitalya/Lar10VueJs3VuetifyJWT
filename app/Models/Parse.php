<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parse extends Model
{
    use HasFactory;

    protected $guarded = false;
    protected $table = 'parses';
}
