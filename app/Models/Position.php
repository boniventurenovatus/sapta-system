<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Position extends Model
{
    use HasFactory;

    protected $table = 'positions';

    protected $fillable = [
        'organizational_unit_id',
        'region_id',
        'district_id',
        'ward_id',
        'region_id',
        'district_id',
        'ward_id',
        'title',
        'code',
        'description',
        'status',
    ];

    public function organizationalUnit(): BelongsTo
    {
        return $this->belongsTo(
            OrganizationalUnit::class,
            'organizational_unit_id'
        );
    }

    public function employeePositions(): HasMany
    {
        return $this->hasMany(
            EmployeePosition::class,
            'position_id'
        );
    }

    public function employees()
    {
        return $this->belongsToMany(
            Employee::class,
            'employee_positions',
            'position_id',
            'employee_id'
        )->withPivot([
            'start_date',
            'end_date',
            'is_primary',
            'status',
            'notes',
        ])->withTimestamps();
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(
            Permission::class,
            'position_permissions',
            'position_id',
            'permission_id'
        )->withTimestamps();
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class, 'region_id');
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class, 'district_id');
    }

    public function ward(): BelongsTo
    {
        return $this->belongsTo(Ward::class, 'ward_id');
    }

    }

