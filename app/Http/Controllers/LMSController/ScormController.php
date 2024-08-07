<?php

namespace App\Http\Controllers\LMSController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Peopleaps\Scorm\Entity\Scorm;
use Peopleaps\Scorm\Model\ScormModel;
use Peopleaps\Scorm\Model\ScormScoTrackingModel;

class ScormController extends Controller
{
    public function trackProgress(Request $request, ScormModel $scormModel)
    {
        $user = Auth::user();

        $tracking = ScormScoTrackingModel::updateOrCreate(
            [
                'user_id' => $user->id,
                'scorm_sco_id' => $request->scorm_sco_id,
            ],
            [
                'entry' => $request->entry,
                'exit' => $request->exit,
                'score_raw' => $request->score_raw,
                'score_max' => $request->score_max,
                'score_min' => $request->score_min,
                'total_time' => $request->total_time,
                'session_time' => $request->session_time,
                'lesson_status' => $request->lesson_status,
            ]
        );

        return response()->json(['status' => 'success']);
    }
}
