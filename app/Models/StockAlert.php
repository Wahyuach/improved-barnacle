<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockAlert extends Model
{
    protected $fillable = [
        'product_id',
        'alert_type',
        'is_resolved',
        'resolved_at',
    ];

    protected $casts = [
        'is_resolved' => 'boolean',
        'resolved_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getAlertIconAttribute(): string
    {
        return match ($this->alert_type) {
            'low_stock' => '⚠️',
            'overstocked' => '📈',
            'expiring' => '⏰',
            default => '❓',
        };
    }

    public function getAlertLabelAttribute(): string
    {
        return match ($this->alert_type) {
            'low_stock' => 'Stok Rendah',
            'overstocked' => 'Stok Berlebih',
            'expiring' => 'Akan Habis',
            default => 'Tidak Diketahui',
        };
    }
}
