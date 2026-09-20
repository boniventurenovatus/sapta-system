<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PerformanceKpi extends Model
{
    use HasFactory;

    protected $table = 'performance_kpis';

    protected $fillable = [
        'performance_review_id', 'kpi_name', 'description',
        'target', 'achieved', 'rating', 'weight',
    ];

    protected $casts = [
        'rating' => 'decimal:1',
    ];

    public function review(): BelongsTo
    {
        return $this->belongsTo(PerformanceReview::class, 'performance_review_id');
    }
}
