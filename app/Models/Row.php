<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Row extends Model
{
    use HasFactory;

    protected $casts = [
        'date' => 'datetime',
    ];

    protected $fillable = [
        'excel_id',
        'name',
        'date',
    ];
}
