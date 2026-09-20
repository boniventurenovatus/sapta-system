<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'employee_id',
        'username',
        'email',
        'profile_image',
        'password_hash',
        'account_status',
        'is_first_login',
        'first_password_expires_at',
        'failed_login_attempts',
        'locked_until',
        'last_login_at',
        'password_changed_at',
        'email_verified_at',
        'remember_token',
    ];

    protected $hidden = [
        'password_hash',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'first_password_expires_at' => 'datetime',
            'locked_until' => 'datetime',
            'last_login_at' => 'datetime',
            'password_changed_at' => 'datetime',
            'is_first_login' => 'boolean',
        ];
    }

    /**
     * Employee linked to this login account.
     */
    public function employee()
    {
        return $this->belongsTo(
            Employee::class,
            'employee_id',
            'id'
        );
    }

    /**
     * Roles assigned to this user.
     *
     * user_roles.user_id -> users.id
     * user_roles.role_id  -> roles.id
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            Role::class,
            'user_roles',
            'user_id',
            'role_id'
        );
    }

    /**
     * Check whether the user has a specific role.
     */
    public function hasRole(string $role): bool
    {
        return $this->roles()
            ->where('name', $role)
            ->exists();
    }

    /**
     * Check whether the user has any of the given roles.
     */
    public function hasAnyRole(array $roles): bool
    {
        return $this->roles()
            ->whereIn('name', $roles)
            ->exists();
    }

    /**
     * Check whether the user has a permission.
     *
     * Assumes:
     * roles -> role_permissions -> permissions
     */
    public function hasPermission(string $permission): bool
    {
        return $this->roles()
            ->whereHas('permissions', function ($query) use ($permission) {
                $query->where('name', $permission);
            })
            ->exists();
    }

    /**
     * Tell Laravel that the password is stored in password_hash.
     */
    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    /**
     * Tell Laravel the password column name.
     */
    public function getAuthPasswordName()
    {
        return 'password_hash';
    }

    /**
     * Set password using password_hash.
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password_hash'] = Hash::make($value);
    }
}