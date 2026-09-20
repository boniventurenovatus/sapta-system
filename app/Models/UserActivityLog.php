<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class UserActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'module',
        'description',
        'subject_type',
        'subject_id',
        'properties',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'properties' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    public function getActionIconAttribute(): string
    {
        return match ($this->action) {
            'login'      => 'sign-in-alt',
            'logout'     => 'sign-out-alt',
            'create'     => 'plus-circle',
            'update'     => 'edit',
            'delete'     => 'trash',
            'view'       => 'eye',
            'suspend'    => 'ban',
            'activate'   => 'check-circle',
            'approve'    => 'check-double',
            'reject'     => 'times-circle',
            default      => 'circle',
        };
    }

    public function getActionColorAttribute(): string
    {
        return match ($this->action) {
            'login'      => 'green',
            'logout'     => 'gray',
            'create'     => 'blue',
            'update'     => 'yellow',
            'delete'     => 'red',
            'view'       => 'gray',
            'suspend'    => 'red',
            'activate'   => 'green',
            'approve'    => 'green',
            'reject'     => 'red',
            default      => 'gray',
        };
    }

    public static function log(
        string $action,
        ?string $module = null,
        ?string $description = null,
        ?int $subjectId = null,
        ?string $subjectType = null,
        ?array $properties = null
    ): self {
        return static::create([
            'user_id'      => auth()->id(),
            'action'       => $action,
            'module'       => $module,
            'description'  => $description,
            'subject_id'   => $subjectId,
            'subject_type' => $subjectType,
            'properties'   => $properties,
            'ip_address'   => request()->ip(),
            'user_agent'   => request()->userAgent(),
        ]);
    }
}