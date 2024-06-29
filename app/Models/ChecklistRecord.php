<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChecklistRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'real_qty',
        'minus_qty',
        'status',
        'note',
        'updated_by',
        'status_verif',
        'created_at',
        'updated_at',
    ];

    protected $appends = [
        'item_name',
        'item_standard_qty',
        'updated_by_name',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($checklistRecord) {
            $checklistRecord->minus_qty = ($checklistRecord->item->standard_qty != 0 && $checklistRecord->real_qty < $checklistRecord->item->standard_qty)
                ? $checklistRecord->item->standard_qty - $checklistRecord->real_qty
                : 0;
        });

        static::updating(function ($checklistRecord) {
            $checklistRecord->minus_qty = ($checklistRecord->item->standard_qty != 0 && $checklistRecord->real_qty < $checklistRecord->item->standard_qty)
                ? $checklistRecord->item->standard_qty - $checklistRecord->real_qty
                : 0;
        });
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function getItemNameAttribute()
    {
        return $this->item->name;
    }

    public function getItemStandardQtyAttribute()
    {
        return $this->item->standard_qty ?? 0;
    }

    public function getUpdatedByNameAttribute()
    {
        return $this->updatedBy?->name ?? '';
    }

    public function scopeNotVerified($query)
    {
        return $query->where('status_verif', 'unverified')
            ->whereNotNull('updated_by')
            ->orderBy('updated_at', 'desc');
    }
}
