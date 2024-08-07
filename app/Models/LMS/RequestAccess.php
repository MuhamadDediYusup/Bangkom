<?php

namespace App\Models\LMS;

use App\Models\PegawaiModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestAccess extends Model
{
    use HasFactory;

    protected $table = 'lms_access_requests';
    protected $primaryKey = 'request_id';

    const CREATED_AT = 'entry_time';
    const CREATED_BY = 'entry_user';
    const UPDATED_AT = 'edit_time';
    const UPDATED_BY = 'edit_user';

    protected $fillable = [
        'request_id',
        'course_id',
        'user_id',
        'requested_at',
        'status',
        'entry_time',
        'edit_time',
        'entry_user',
        'edit_user',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function employee()
    {
        return $this->belongsTo(PegawaiModel::class, 'user_id', 'nip');
    }
}
