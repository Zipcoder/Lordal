<?php

namespace App\Jobs;

use DB;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class GenerateReportData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Get student data with lab percentage completion rates
        $student_lab_percentage_data = DB::table('students')->join('users', 'users.id', 'students.user_id')
            ->where(['students.dismissed' => false, 'students.dont_track' => false])
            ->join('assignments', 'students.id', 'assignments.student_id')
            ->leftJoin('submissions', 'submissions.assignment_id', 'assignments.id')
            ->select(['students.id', 'users.name', DB::raw("ROUND(COUNT(submissions.id)::numeric / COUNT(assignments.id) * 100, 2) as lab_completion_percentage")])
            ->groupBy('students.id', 'users.name')
            ->get();

        $student_lab_percentage_data->transform(function($student) {
            return [
                'student_id' => $student->id,
                'student_name' => $student->name,
                'lab_completion_percentage' => $student->lab_completion_percentage,
            ];
        });

        // Get each individual lab's percentage completion rate
        $assessment_percentage_completion_data = DB::table('assessments')
            ->where('assessments.level', "Lab")
            ->join('assignments', 'assessments.id', 'assignments.assessment_id')
            ->leftJoin('submissions', 'submissions.assignment_id', 'assignments.id')
            ->join('students', 'students.id', 'assignments.student_id')
            ->where('students.dont_track', false)
            ->groupBy('assessments.id', 'assessments.name', 'assessments.url')
            ->select(['assessments.id', 'assessments.name', DB::raw("ROUND(COUNT(submissions.id)::numeric / COUNT(assignments.id) * 100, 2) as lab_completion_percentage")])
            ->get();

        $assessment_percentage_completion_data->transform(function($assessment) {
            return [
                'assessment_id' => $assessment->id,
                'lab_name' => $assessment->name,
                'completion_rate' => $assessment->lab_completion_percentage,
            ];
        });

        // Get each Test and it's average score as well as ther
        // number of students that passed and failed
        $quiz_and_exam_data = DB::table('assessments')
            ->orWhere(['assessments.level' => "Exam", 'assessments.level' => "Quiz"])
            ->join('assignments', 'assessments.id', 'assignments.assessment_id')
            ->leftJoin('submissions', 'submissions.assignment_id', 'assignments.id')
            ->join('students', 'students.id', 'assignments.student_id')
            ->where('students.dont_track', false)
            ->groupBy('assessments.id', 'assessments.name', 'assessments.url')
            ->select(['assessments.id', 'assessments.name', DB::raw('MIN(submissions.grade) as min_score'), DB::raw('MAX(submissions.grade) as max_score'), 'assessments.level', DB::raw("ROUND(AVG(submissions.grade), 2) as average_score"), DB::raw("ROUND(AVG(submissions.grade) / assessments.max_score * 100, 2) as average_percentage")])
            ->get();
        // Save the data to a persistant source
        DB::table('student_assessments_analytics')->insert($student_lab_percentage_data->toArray());
        DB::table('assessments_analytics')->insert($assessment_percentage_completion_data->toArray());

        $quiz_and_exam_data->each(function($exam, $key) {
            DB::table('exam_analytics')->updateOrInsert(
                ['assessment_id' => $exam->id],
                [
                    'assessment_name' => $exam->name,
                    'average_grade' => $exam->average_percentage,
                    'max_score' => $exam->max_score,
                    'min_score' => $exam->min_score,
                ]
            );
        });
        // Forget old cache and generate cache the data for 96 hours
        // TODO: set up cache
    }
}
