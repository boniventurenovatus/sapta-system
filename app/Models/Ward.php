<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ward extends Model
{
    protected $table = 'wards';
    protected $fillable = ['district_id', 'name', 'code', 'status'];

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }
}
