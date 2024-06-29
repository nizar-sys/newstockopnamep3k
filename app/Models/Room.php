<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    protected $appends = ['last_changes_date'];

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function getLastChangesDateAttribute()
    {
        $result = null;
        $checklistRecords = $this
            ->items()
            ->with('checklistRecords')
            ->get()
            ->flatMap(function ($item) {
                return $item->checklistRecords;
            })
            ->groupBy(function ($record) {
                return $record->created_at->format('Y-m-d');
            })
            ->sortByDesc(function ($records, $key) {
                return $records->first()->updated_at;
            });
        // ->sortKeysDesc();

        if ($checklistRecords->count() > 0) {
            foreach ($checklistRecords as $key => $checklistRecord) {

                if ($checklistRecord->contains('status_verif', 'verified')) {
                    $checklistRecords->forget($key);
                    $result = $key;
                    break;
                }
            }
        }

        return $result;
    }
}
