<?php

namespace App\Http\Controllers\Pages;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StaffPages extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'auth.staff']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return redirect('/staff/students');
    }

    public function assessments() {
        return view('assessments');
    }

    public function create_assessment() {
        return view('create_assessment');
    }

    public function students() {
        return view('students');
    }

    public function student($student_id) {
        return view('show_student')
            ->with(['student_id' => $student_id]);
    }

    public function exams() {
        return view('exams');
    }

    public function quizzes() {
        return view('quizzes');
    }

    public function labs() {
        return view('labs');
    }

    public function tokens() {
        return view('tokens');
    }

    public function reports() {
        return view('reports');
    }

    public function student_breakdown_reports($student_id) {
        return view('student_breakdown_reports')
            ->with(['student_id' => $student_id]);

    }

    public function updateAssessment($assessment_id) {
        return view('update_assessment')
            ->with(['assessment_id' => $assessment_id]);
    }

}
