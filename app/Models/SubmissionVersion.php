<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubmissionVersion extends Model
{
    protected $fillable = [
        'submission_id', 'version_number', 'data',
        'created_by', 'change_notes', 'action',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public function submission()
    {
        return $this->belongsTo(Submission::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}