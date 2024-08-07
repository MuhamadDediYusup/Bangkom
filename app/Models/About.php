<?php

namespace App\Models;

use App\Traits\Blameable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class About extends Model
{
    use HasFactory;
    use Blameable;
    protected $table = 'about';
    protected $primaryKey = 'id_about';
    const CREATED_AT = 'entry_time';
    const UPDATED_AT = 'edit_time';
    protected $fillable = [
        'text_about',
        'entry_user',
        'entry_time',
        'edit_user',
        'edit_time',
    ];
}
