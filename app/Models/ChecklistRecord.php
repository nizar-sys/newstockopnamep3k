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
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
