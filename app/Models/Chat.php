<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = [
        'message',
        'from',
        'to',
        'isRead'
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i'
    ];
}
