<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Training extends Model
{
    protected $fillable = [
        'title', 'type', 'start_date', 'end_date', 'duration_hours', 'cost',
        'region_id', 'district_id', 'ward_id',
        'organization_id', 'department_id',
        'trainer_name', 'trainer_email', 'max_participants', 'venue',
        'description', 'status', 'created_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];
}