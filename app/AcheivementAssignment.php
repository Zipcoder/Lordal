<?php

namespace App;

class AcheivementAssignment extends BaseModel
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
        return $this->belongsTo(Acheivement::class);
    }

/*    public function submission() {
        return $this->hasOne(Submission::class);
    }
*/
}
