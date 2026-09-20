<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'roles';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'code',
        'description',
        'status',
    ];

    /**
     * The attributes that should be cast.
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
     * Permissions assigned to this role.
     *
     * roles
     *   ↓
     * role_permissions
     *   ↓
     * permissions
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(
            Permission::class,
            'role_permissions',
            'role_id',
            'permission_id'
        )->withTimestamps();
    }

    /**
     * Users assigned to this role.
     *
     * roles
     *   ↓
     * user_roles
     *   ↓
     * users
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'user_roles',
            'role_id',
            'user_id'
        )->withTimestamps();
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    /**
     * Scope active roles.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope inactive roles.
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    /**
     * Search roles by name, code or description.
     */
    public function scopeSearch($query, ?string $search)
    {
        if (blank($search)) {
            return $query;
        }

        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', '%' . $search . '%')
                ->orWhere('code', 'like', '%' . $search . '%')
                ->orWhere('description', 'like', '%' . $search . '%');
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Permission Helpers
    |--------------------------------------------------------------------------
    */

    /**
     * Check whether this role has a specific permission.
     *
     * Example:
     *
     * $role->hasPermission('employees.view');
     */
    public function hasPermission(string $permission): bool
    {
        return $this->permissions()
            ->where(function ($query) use ($permission) {
                $query->where('code', $permission)
                    ->orWhere('name', $permission);
            })
            ->where('permissions.status', 'active')
            ->exists();
    }

    /**
     * Assign a permission to this role.
     *
     * Accepts:
     * - Permission model
     * - Permission ID
     */
    public function assignPermission(Permission|int $permission): static
    {
        $permissionId = $permission instanceof Permission
            ? $permission->getKey()
            : $permission;

        $this->permissions()->syncWithoutDetaching([
            $permissionId,
        ]);

        return $this;
    }

    /**
     * Assign multiple permissions to this role.
     *
     * Example:
     *
     * $role->assignPermissions([1, 2, 3]);
     */
    public function assignPermissions(array $permissions): static
    {
        $permissionIds = collect($permissions)
            ->map(function ($permission) {
                return $permission instanceof Permission
                    ? $permission->getKey()
                    : $permission;
            })
            ->filter()
            ->unique()
            ->values()
            ->all();

        if (!empty($permissionIds)) {
            $this->permissions()->syncWithoutDetaching($permissionIds);
        }

        return $this;
    }

    /**
     * Remove a permission from this role.
     */
    public function removePermission(Permission|int $permission): static
    {
        $permissionId = $permission instanceof Permission
            ? $permission->getKey()
            : $permission;

        $this->permissions()->detach($permissionId);

        return $this;
    }

    /**
     * Remove all permissions from this role.
     */
    public function removeAllPermissions(): static
    {
        $this->permissions()->detach();

        return $this;
    }

    /**
     * Replace all permissions assigned to this role.
     */
    public function syncPermissions(array $permissions): static
    {
        $permissionIds = collect($permissions)
            ->map(function ($permission) {
                return $permission instanceof Permission
                    ? $permission->getKey()
                    : $permission;
            })
            ->filter()
            ->unique()
            ->values()
            ->all();

        $this->permissions()->sync($permissionIds);

        return $this;
    }

    /*
    |--------------------------------------------------------------------------
    | User Helpers
    |--------------------------------------------------------------------------
    */

    /**
     * Check whether a specific user belongs to this role.
     */
    public function hasUser(User|int $user): bool
    {
        $userId = $user instanceof User
            ? $user->getKey()
            : $user;

        return $this->users()
            ->where('users.id', $userId)
            ->exists();
    }

    /**
     * Assign this role to a user.
     */
    public function assignToUser(User|int $user): static
    {
        $userId = $user instanceof User
            ? $user->getKey()
            : $user;

        $this->users()->syncWithoutDetaching([
            $userId,
        ]);

        return $this;
    }

    /**
     * Remove this role from a user.
     */
    public function removeFromUser(User|int $user): static
    {
        $userId = $user instanceof User
            ? $user->getKey()
            : $user;

        $this->users()->detach($userId);

        return $this;
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    /**
     * Get a human-readable status label.
     */
    public function getStatusLabelAttribute(): string
    {
        return $this->status === 'active'
            ? 'Active'
            : 'Inactive';
    }

    /**
     * Get the number of assigned permissions.
     */
    public function getPermissionsCountAttribute(): int
    {
        if ($this->relationLoaded('permissions')) {
            return $this->permissions->count();
        }

        return $this->permissions()->count();
    }

    /**
     * Get the number of assigned users.
     */
    public function getUsersCountAttribute(): int
    {
        if ($this->relationLoaded('users')) {
            return $this->users->count();
        }

        return $this->users()->count();
    }
}