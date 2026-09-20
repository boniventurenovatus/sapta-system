<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Employee extends Model
{
    use HasFactory, SoftDeletes;


    /*
    |--------------------------------------------------------------------------
    | TABLE
    |--------------------------------------------------------------------------
    */

    protected $table = 'employees';


    /*
    |--------------------------------------------------------------------------
    | MASS ASSIGNABLE
    |--------------------------------------------------------------------------
    */

    protected $fillable = [

        'organization_id',

        'department_id',

        'organizational_unit_id',

        'employee_number',

        'first_name',

        'middle_name',

        'last_name',

        'gender',

        'date_of_birth',

        'phone',

        'email',

        'profile_image',

        'hire_date',

        'job_title',

        'employment_status',

        'address',

        'notes',

    ];


    /*
    |--------------------------------------------------------------------------
    | CASTS
    |--------------------------------------------------------------------------
    */

    protected function casts(): array
    {
        return [

            'date_of_birth' => 'date',

            'hire_date' => 'date',

        ];
    }


    /*
    |--------------------------------------------------------------------------
    | ORGANIZATION
    |--------------------------------------------------------------------------
    */

    public function organization(): BelongsTo
    {
        return $this->belongsTo(
            Organization::class,
            'organization_id',
            'id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | DEPARTMENT
    |--------------------------------------------------------------------------
    */

    public function department(): BelongsTo
    {
        return $this->belongsTo(
            Department::class,
            'department_id',
            'id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ORGANIZATIONAL UNIT
    |--------------------------------------------------------------------------
    */

    public function organizationalUnit(): BelongsTo
    {
        return $this->belongsTo(
            OrganizationalUnit::class,
            'organizational_unit_id',
            'id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | USER ACCOUNT
    |--------------------------------------------------------------------------
    |
    | users.employee_id -> employees.id
    |
    */

    public function user(): HasOne
    {
        return $this->hasOne(
            User::class,
            'employee_id',
            'id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ATTENDANCE
    |--------------------------------------------------------------------------
    */

    public function attendances(): HasMany
    {
        return $this->hasMany(
            Attendance::class,
            'employee_id',
            'id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | FULL NAME
    |--------------------------------------------------------------------------
    */

    public function getFullNameAttribute(): string
    {
        return trim(
            collect([

                $this->first_name,

                $this->middle_name,

                $this->last_name,

            ])
                ->filter()
                ->implode(' ')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | INITIALS
    |--------------------------------------------------------------------------
    */

    public function getInitialsAttribute(): string
    {
        return collect([

            $this->first_name,

            $this->middle_name,

            $this->last_name,

        ])
            ->filter()
            ->map(
                fn ($name) =>
                    strtoupper(
                        substr($name, 0, 1)
                    )
            )
            ->implode('');
    }


    /*
    |--------------------------------------------------------------------------
    | PROFILE IMAGE URL
    |--------------------------------------------------------------------------
    */

    public function getProfileImageUrlAttribute(): ?string
    {
        if (empty($this->profile_image)) {
            return null;
        }

        return asset(
            'storage/' .
            ltrim(
                $this->profile_image,
                '/'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | DISPLAY NAME
    |--------------------------------------------------------------------------
    */

    public function getDisplayNameAttribute(): string
    {
        if ($this->employee_number) {

            return $this->full_name .
                ' (' .
                $this->employee_number .
                ')';

        }

        return $this->full_name;
    }


    /*
    |--------------------------------------------------------------------------
    | AGE
    |--------------------------------------------------------------------------
    */

    public function getAgeAttribute(): ?int
    {
        if (!$this->date_of_birth) {
            return null;
        }

        return $this->date_of_birth->age;
    }


    /*
    |--------------------------------------------------------------------------
    | YEARS OF SERVICE
    |--------------------------------------------------------------------------
    */

    public function getYearsOfServiceAttribute(): ?int
    {
        if (!$this->hire_date) {
            return null;
        }

        return $this->hire_date->diffInYears(now());
    }


    /*
    |--------------------------------------------------------------------------
    | ACTIVE
    |--------------------------------------------------------------------------
    */

    public function isActive(): bool
    {
        return strtolower(
            (string) $this->employment_status
        ) === 'active';
    }


    /*
    |--------------------------------------------------------------------------
    | USER ACCOUNT EXISTS
    |--------------------------------------------------------------------------
    */

    public function hasUserAccount(): bool
    {
        return $this->user()->exists();
    }


    /*
    |--------------------------------------------------------------------------
    | LATEST ATTENDANCE
    |--------------------------------------------------------------------------
    */

    public function latestAttendance()
    {
        return $this->hasOne(
            Attendance::class,
            'employee_id',
            'id'
        )->latestOfMany(
            'attendance_date'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ACTIVE SCOPE
    |--------------------------------------------------------------------------
    */

    public function scopeActive($query)
    {
        return $query->where(
            'employment_status',
            'active'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | INACTIVE SCOPE
    |--------------------------------------------------------------------------
    */

    public function scopeInactive($query)
    {
        return $query->where(
            'employment_status',
            '!=',
            'active'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | SEARCH SCOPE
    |--------------------------------------------------------------------------
    */

    public function scopeSearch(
        $query,
        string $search
    ) {

        return $query->where(function ($q) use ($search) {

            $q->where(
                'employee_number',
                'like',
                "%{$search}%"
            )

            ->orWhere(
                'first_name',
                'like',
                "%{$search}%"
            )

            ->orWhere(
                'middle_name',
                'like',
                "%{$search}%"
            )

            ->orWhere(
                'last_name',
                'like',
                "%{$search}%"
            )

            ->orWhere(
                'email',
                'like',
                "%{$search}%"
            )

            ->orWhere(
                'phone',
                'like',
                "%{$search}%"
            )

            ->orWhere(
                'job_title',
                'like',
                "%{$search}%"
            );

        });

    }
}