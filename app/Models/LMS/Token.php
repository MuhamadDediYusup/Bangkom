<?php

namespace App\Models\LMS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    use HasFactory;

    protected $table = 'lms_access_tokens';
    protected $primaryKey = 'token_id';

    const CREATED_AT = 'entry_time';
    const CREATED_BY = 'entry_user';
    const UPDATED_AT = 'edit_time';
    const UPDATED_BY = 'edit_user';


    protected $fillable = [
        'token_id',
        'token',
        'course_id',
        'is_active',
        'entry_user',
        'edit_user'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
}
