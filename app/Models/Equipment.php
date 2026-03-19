<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

/**
 * Equipment Model
 *
 * Represents a physical piece of gym equipment with full lifecycle tracking.
 */
class Equipment extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Equipment status constants.
     */
    public const STATUS_ACTIVE = 'active';
    public const STATUS_UNDER_MAINTENANCE = 'under_maintenance';
    public const STATUS_FAULTY = 'faulty';
    public const STATUS_DECOMMISSIONED = 'decommissioned';

    /**
     * Mass assignable attributes.
     */
    protected $fillable = [
        "gym_id",
        "category_id",
        "name",
        "brand",
        "model_number",
        "usage_notes",
        "manufacturer_serial_no",
        "asset_code",
        "purchase_price",
        "purchase_date",
        "warranty_expiration",
        "status",
        "is_safety_hazard",
        "last_serviced_at",
        "next_service_due_at",
        "service_interval_days",
        "floor_location",
    ];

    /**
     * Attribute casting.
     * Ensures dates are Carbon instances and booleans are true/false.
     */
    protected $casts = [
        'purchase_price' => 'decimal:2',
        'purchase_date' => 'date',
        'warranty_expiration' => 'date',
        'last_serviced_at' => 'date',
        'next_service_due_at' => 'date',
        'is_safety_hazard' => 'boolean',
        'service_interval_days' => 'integer',
    ];

    /**---------------
     * Relationships
    -----------------*/

    public function gym()
    {
        return $this->belongsTo(Gym::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**--------------
     * Query Scopes
    -----------------*/

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeFaulty($query)
    {
        return $query->where('status', self::STATUS_FAULTY);
    }

    public function scopeMaintenanceOverdue($query)
    {
        return $query->where('next_service_due_at', '<', now())
            ->where('status', '!=', self::STATUS_DECOMMISSIONED);
    }

    public function scopeSafetyHazards($query)
    {
        return $query->where('is_safety_hazard', true);
    }

    /**--------------
     * Accessors / Helpers
    -----------------*/

    /**
     * Check if the warranty has expired.
     */
    public function isWarrantyExpired(): bool
    {
        if (!$this->warranty_expiration) {
            return false;
        }

        // Explicitly wrap in Carbon to ensure isPast() is available
        return Carbon::parse($this->warranty_expiration)->isPast();
    }

    /**
     * Check if service is overdue.
     */
    public function isServiceOverdue(): bool
    {
        if (!$this->next_service_due_at) {
            return false;
        }

        // Explicitly wrap in Carbon
        return Carbon::parse($this->next_service_due_at)->isPast();
    }
}
