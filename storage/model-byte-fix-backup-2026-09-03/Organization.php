<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Organization extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'organizations';

    protected $fillable = [
        'name',
        'code',
        'description',
        'email',
        'phone',
        'address',
        'website',
        'status',
    ];

    public function employees(): HasMany
    {
        return $this->hasMany(
            Employee::class,
            'organization_id'
        );
    }

    public function organizationalUnits(): HasMany
    {
        return $this->hasMany(
            OrganizationalUnit::class,
            'organization_id'
        );
    }

    public function projects(): HasMany
    {
        return $this->hasMany(
            Project::class,
            'organization_id'
        );
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->code
            ? $this->name . ' (' . $this->code . ')'
            : $this->name;
    }
}
