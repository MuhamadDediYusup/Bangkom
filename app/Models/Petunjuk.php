<?php

namespace App\Models;

use App\Traits\Blameable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Petunjuk extends Model
{
    use HasFactory;
    use Blameable;
    protected $table = 'petunjuk';
    protected $primaryKey = 'id_petunjuk';
    const CREATED_AT = 'entry_time';
    const UPDATED_AT = 'edit_time';
    protected $fillable = [
        'file_petunjuk',
        'entry_user',
        'entry_time',
        'edit_user',
        'edit_time',
    ];
}
