<?php

namespace App\Http\Controllers;

use App\Student;
use App\Assessment;
use App\Assignment;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AssessmentStudentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  int  $assessment_id
     * @return \Illuminate\Http\Response
     */
    public function index($assessment_id)
    {
        $assessment = Assessment::with('students.pivot.submission')
            ->with('students.user')
            ->find($assessment_id);
        return response()->json($assessment);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $assessment_id
     * @param  int  $student_id
     * @return \Illuminate\Http\Response
     */
    public function show($assessment_id, $student_id)
    {
        $assessment = Assessment::with('students.pivot.submission')
            ->where('students.id', $student_id)->find($assessment_id);

        return response()->json($assessment, Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Assessment $assessment
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Assessment $assessment)
    {
        $student_ids = Student::with('user')
            ->whereNotIn('students.id',
                Assignment::where('assessment_id', $assessment->id)
                    ->select('student_id'))
            ->where(['dismissed' => false])
            ->get();

        $assessment->students()->attach($student_ids);
        return response('', Response::HTTP_CREATED);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Assessment  $assessment
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Assessment $assessment, Student $student)
    {
        $assessment->students()->attach($student);

        return response()->json( '', Response::HTTP_CREATED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Assessment  $assessment
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function destroy(Assessment $assessment, Student $student)
    {
        $assessment->students()->detach($student);

        return response()->json( '', Response::HTTP_ACCEPTED);
    }

}
