<?php

namespace App;

use App\Pivots\AssignmentPivot;

class Student extends BaseModel
{
    protected $fillable = [
          'cell_number'
        , 'github_id'
        , 'github_username'
        , 'section'
        , 'serial_number'
        , 'user_id'
        , 'dismissed'
        , 'dont_track'
    ];

    protected $casts = [
        'dismissed' => 'boolean',
        'dont_track' => 'boolean',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments() {
        return $this->hasMany(Comment::class)
            ->orderBy('created_at', 'DESC');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function assessments() {
        return $this->belongsToMany(Assessment::class, 'assignments')
            ->using(AssignmentPivot::class)
            ->withPivot(['id']);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function submissions() {
        return $this->hasManyThrough(Submission::class, Assignment::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user() {
        return $this->belongsTo(User::class);
    }
}
