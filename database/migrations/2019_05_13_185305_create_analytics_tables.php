<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnalyticsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_assessments_analytics', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('student_id');
            $table->string("student_name");
            $table->float('lab_completion_percentage', 4, 2);
            $table->timestamps();
        });

        Schema::create('assessments_analytics', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('assessment_id');
            $table->string("lab_name");
            $table->float('completion_rate', 4, 2);
            $table->timestamps();
        });

        Schema::create('exam_analytics', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('assessment_id');
            $table->unsignedInteger('max_score');
            $table->unsignedInteger('min_score');
            $table->string("assessment_name");
            $table->float('average_grade', 4, 2);
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('student_assessments_analytics');
        Schema::dropIfExists('assessments_analytics');
        Schema::dropIfExists('exam_analytics');
    }
}
