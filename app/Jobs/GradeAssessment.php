<?php

namespace App\Jobs;

use Log;
use App\Submission;
use App\Assessment;
use Illuminate\Bus\Queueable;
use App\Bus\PendingRawDispatch;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class GradeAssessment implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $payload;

    /**
     * Dispatch the job with the given arguments.
     *
     * @return \Illuminate\Foundation\Bus\PendingDispatch
     */
    public static function dispatch()
    {
        return (new PendingRawDispatch(new static(...func_get_args())))
            ->onConnection(\Config::get('queue.no_handle'));
    }

    /**
     * Create a new job instance.
     *
     * @param Submission $submission
     * @param Asessment $assessment
     */
    public function __construct(Submission $submission, Assessment $assessment)
    {
        $this->payload = json_encode([
            "submission" => $submission,
            "assessment" => $assessment,
        ]);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::error("Grading Execution should not be handled by Laravel");
    }
}
