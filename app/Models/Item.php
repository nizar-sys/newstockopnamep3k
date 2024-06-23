<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = ['room_id', 'name', 'standard_qty'];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function checklistRecords()
    {
        return $this->hasMany(ChecklistRecord::class);
    }
}
