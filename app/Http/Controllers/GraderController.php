<?php

namespace App\Http\Controllers;

use App\Assignment;
use Illuminate\Http\Request;
use App\Jobs\GradeAssessment;

class GraderController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, $assignment_id)
    {
        $assignment = Assignment::with(['assessment', 'submission'])->find($assignment_id);
        $assessment = $assignment->assessment;
        $submission = $assignment->submission;

        GradeAssessment::dispatch($submission, $assessment);
    }
}
