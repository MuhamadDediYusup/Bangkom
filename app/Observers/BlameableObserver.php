<?php

namespace App\Observers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class BlameableObserver
{
    public function creating(Model $model)
    {
        $model->entry_user = Auth::user()->user_id;
        $model->edit_user = Auth::user()->user_id;
    }

    public function updating(Model $model)
    {
        $model->edit_user = Auth::user()->user_id;
    }
}