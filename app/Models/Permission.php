<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | Table
    |--------------------------------------------------------------------------
    */

    protected $table = 'permissions';

    /*
    |--------------------------------------------------------------------------
    | Mass Assignment
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'name',
        'code',
        'description',
        'status',
    ];

    /*
    |--------------------------------------------------------------------------
    | Attribute Casting
    |--------------------------------------------------------------------------
    */

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Roles that have this permission.
     *
     * permissions
     *      â†“
     * role_permissions
     *      â†“
     * roles
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            Role::class,
            'role_permissions',
            'permission_id',
            'role_id'
        )->withTimestamps();
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    /**
     * Get active permissions only.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Get inactive permissions only.
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    /**
     * Search by name, code or description.
     */
    public function scopeSearch($query, ?string $search)
    {
        if (blank($search)) {
            return $query;
        }

        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', '%' . $search . '%')
                ->orWhere('code', 'like', '%' . $search . '%')
                ->orWhere(
                    'description',
                    'like',
                    '%' . $search . '%'
                );
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Permission Helpers
    |--------------------------------------------------------------------------
    */

    /**
     * Check whether this permission is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check whether this permission is inactive.
     */
    public function isInactive(): bool
    {
        return $this->status === 'inactive';
    }

    /*
    |--------------------------------------------------------------------------
    | Role Helpers
    |--------------------------------------------------------------------------
    */

    /**
     * Check whether this permission belongs to a role.
     */
    public function hasRole(Role|int $role): bool
    {
        $roleId = $role instanceof Role
            ? $role->getKey()
            : $role;

        return $this->roles()
            ->where('roles.id', $roleId)
            ->exists();
    }

    /**
     * Assign this permission to a role.
     */
    public function assignToRole(Role|int $role): static
    {
        $roleId = $role instanceof Role
            ? $role->getKey()
            : $role;

        $this->roles()->syncWithoutDetaching([
            $roleId,
        ]);

        return $this;
    }

    /**
     * Remove this permission from a role.
     */
    public function removeFromRole(Role|int $role): static
    {
        $roleId = $role instanceof Role
            ? $role->getKey()
            : $role;

        $this->roles()->detach($roleId);

        return $this;
    }

    /**
     * Remove this permission from all roles.
     */
    public function removeFromAllRoles(): static
    {
        $this->roles()->detach();

        return $this;
    }

    /*
    |--------------------------------------------------------------------------
    | Bulk Role Assignment
    |--------------------------------------------------------------------------
    */

    /**
     * Assign this permission to multiple roles.
     *
     * Example:
     *
     * $permission->assignToRoles([1, 2, 3]);
     */
    public function assignToRoles(array $roles): static
    {
        $roleIds = collect($roles)
            ->map(function ($role) {
                return $role instanceof Role
                    ? $role->getKey()
                    : $role;
            })
            ->filter()
            ->unique()
            ->values()
            ->all();

        if (!empty($roleIds)) {
            $this->roles()->syncWithoutDetaching($roleIds);
        }

        return $this;
    }

    /**
     * Replace all roles assigned to this permission.
     */
    public function syncRoles(array $roles): static
    {
        $roleIds = collect($roles)
            ->map(function ($role) {
                return $role instanceof Role
                    ? $role->getKey()
                    : $role;
            })
            ->filter()
            ->unique()
            ->values()
            ->all();

        $this->roles()->sync($roleIds);

        return $this;
    }

    /*
    |--------------------------------------------------------------------------
    | Query Helpers
    |--------------------------------------------------------------------------
    */

    /**
     * Find permission by code.
     */
    public static function findByCode(string $code): ?self
    {
        return static::query()
            ->where('code', $code)
            ->first();
    }

    /**
     * Find permission by code or fail.
     */
    public static function findByCodeOrFail(string $code): self
    {
        return static::query()
            ->where('code', $code)
            ->firstOrFail();
    }

    /**
     * Find permission by name.
     */
    public static function findByName(string $name): ?self
    {
        return static::query()
            ->where('name', $name)
            ->first();
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    /**
     * Human-readable status.
     */
    public function getStatusLabelAttribute(): string
    {
        return $this->status === 'active'
            ? 'Active'
            : 'Inactive';
    }

    /**
     * Number of roles using this permission.
     */
    public function getRolesCountAttribute(): int
    {
        if ($this->relationLoaded('roles')) {
            return $this->roles->count();
        }

        return $this->roles()->count();
    }

    /**
     * Permission display label.
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->name;
    }

    /**
     * Return code in a readable format.
     *
     * Example:
     * employees.create
     * becomes
     * Employees Create
     */
    public function getFormattedCodeAttribute(): string
    {
        return str($this->code)
            ->replace(['.', '_', '-'], ' ')
            ->title()
            ->toString();
    }
}


