<?php

namespace App;
use Carbon\Carbon;
use App\Pivots\AssignmentPivot;

class Acheivement extends BaseModel
{
    protected $fillable = ['url', 'name', 'level', 'gradable', 'max_score', 'passing_score', 'assigned_date', 'due_date', 'autograde'];

    protected $casts = [
        'assigned_date' => 'datetime:Y-m-d H:i:s',
        'due_date' => 'datetime:Y-m-d H:i:s',
    ];

    protected $dateFormat = 'Y-m-d H:i:s';

    /**
     * Scope a query to only include Labs.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeLabs($query)
    {
        return $query->where('level', 'Lab');
    }

    /**
     * Scope a query to only include Exams.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeExams($query)
    {
        return $query->where('level', 'Exam');
    }

    /**
     * Scope a query to only include Quizzes.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeQuizzes($query)
    {
        return $query->where('level', 'Quiz');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function students() {
        return $this->belongsToMany(Student::class, 'assignments')
            ->using(AssignmentPivot::class)
            ->withPivot(['id']);
    }

    public function submissions() {
        return $this->hasManyThrough(Submission::class, Assignment::class);
    }

}
