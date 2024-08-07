<?php

namespace App\Models\LMS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'lms_categories';
    protected $primaryKey = 'category_id';

    const CREATED_AT = 'entry_time';
    const CREATED_BY = 'entry_user';
    const UPDATED_AT = 'edit_time';
    const UPDATED_BY = 'edit_user';

    protected $fillable = [
        'category_name',
        'entry_user',
        'edit_user'
    ];

    public $timestamps = false;

    protected $dates = [
        'entry_time',
        'edit_time'
    ];

    // Relationships
    public function courses()
    {
        return $this->hasMany(Course::class, 'category_id');
    }
}
