<?php

namespace App;

class AchievementAssignment extends BaseModel
{
    protected $fillable = ['student_id','assessment_id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function student() {
        return $this->belongsTo(Student::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function assessment() {
        return $this->belongsTo(Achievement::class);
    }

/*    public function submission() {
        return $this->hasOne(Submission::class);
    }
*/
}
