<?php

namespace App\Http\Controllers;

use App\Models\ChecklistRecord;
use App\Models\Item;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ChecklistRecordController extends Controller
{
    public function index(Request $request)
    {
        Carbon::setLocale('id');

        $hasDataset = false;
        $room = null;
        $roomItems = collect();
        $dateSelected = $request->filled('date') ? Carbon::parse($request->date)->format('Y-m-d') : now()->format('Y-m-d');
        $checklistRecords = collect();
        $checklistRecordsUnsaved = collect();

        if ($request->filled('room_id')) {
            $room = Room::with('items')->findOrFail($request->room_id);
            $roomItems = $room->items;
            $hasDataset = true;

            $checklistRecords = ChecklistRecord::whereIn('item_id', $roomItems->pluck('id'))
                ->whereDate('created_at', $dateSelected)
                ->get();

            if ($checklistRecords->isEmpty()) {
                $payloadChecklistRecords = $roomItems->map(function ($item) use ($dateSelected) {
                    return [
                        'item_id' => $item->id,
                        'real_qty' => 0,
                        'minus_qty' => 0,
                        'status' => null,
                        'note' => null,
                        'updated_by' => null,
                        'created_at' => $dateSelected,
                        'updated_at' => now(),
                    ];
                })->toArray();

                ChecklistRecord::insert($payloadChecklistRecords);

                $checklistRecords = ChecklistRecord::whereIn('item_id', $roomItems->pluck('id'))
                    ->whereDate('created_at', $dateSelected)
                    ->get();
            }

            $checklistRecordsUnsaved = ChecklistRecord::notVerified()
                ->whereDate('created_at', $dateSelected)
            ->get();
        }

        return view('dashboard.checklist_records.index', compact('hasDataset', 'room', 'roomItems', 'dateSelected', 'checklistRecords', 'checklistRecordsUnsaved'));
    }

    public function store(Request $request)
    {
        $roomItems = Room::with('items')->findOrFail($request->room_id)->items;

        // cek apakah sudah ada data checklist hari ini
        $checklistRecords = ChecklistRecord::whereIn('item_id', collect($request->data)->pluck('item_id'))
            ->whereDate('created_at', $request->filled('date') ? date('Y-m-d', strtotime($request->date)) : date('Y-m-d', strtotime(now())))
            ->get();

        // update data jika sudah ada
        if ($checklistRecords->count() > 0) {
            foreach ($checklistRecords as $checklistRecord) {
                $data = collect($request->data)->firstWhere('item_id', $checklistRecord->item_id);
                $checklistRecord->update([
                    'real_qty' => $data['real_qty'],
                    'minus_qty' => ($checklistRecord->item->standard_qty != 0 && $data['real_qty'] < $checklistRecord->item->standard_qty) ? $checklistRecord->item->standard_qty - $data['real_qty'] : 0,
                    'status' => $data['status'],
                    'note' => $data['note'],
                    'status_verif' => 'unverified',
                    'updated_by' => auth()->id(),
                    'created_at' => $request->filled('date') ? date('Y-m-d', strtotime($request->date)) : date('Y-m-d', strtotime(now())),
                    'updated_at' => now(),
                ]);
            }
        } else {
            $payloadChecklistRecords = [];
            foreach ($roomItems as $item) {
                if ($data = collect($request->data)->firstWhere('item_id', $item->id)) {
                    $payloadChecklistRecords[] = [
                        'item_id' => $item->id,
                        'real_qty' => $data['real_qty'],
                        'minus_qty' => ($item->standard_qty != 0 && $data['real_qty'] < $item->standard_qty) ? $item->standard_qty - $data['real_qty'] : 0,
                        'status' => $data['status'],
                        'note' => $data['note'],
                        'status_verif' => 'unverified',
                        'updated_by' => auth()->id(),
                        'created_at' => $request->filled('date') ? date('Y-m-d', strtotime($request->date)) : date('Y-m-d', strtotime(now())),
                        'updated_at' => now(),
                    ];
                } else {
                    $payloadChecklistRecords[] = [
                        'item_id' => $item->id,
                        'real_qty' => 0,
                        'minus_qty' => 0,
                        'status' => null,
                        'note' => null,
                        'status_verif' => 'unverified',
                        'updated_by' => null,
                        'created_at' => $request->filled('date') ? date('Y-m-d', strtotime($request->date)) : date('Y-m-d', strtotime(now())),
                        'updated_at' => now(),
                    ];
                }
            }

            ChecklistRecord::insert($payloadChecklistRecords);
        }

        return response()->json(['message' => 'Data berhasil disimpan']);
    }

    public function storeItem(Request $request)
    {
        $item = Item::create([
            'room_id' => $request->room_id,
            'name' => $request->name,
            'standard_qty' => $request->standard_qty,
        ]);

        $dateSelected = $request->filled('date') ? date('Y-m-d', strtotime($request->date)) : date('Y-m-d', strtotime(now()));

        $checklistRecords = ChecklistRecord::where('item_id', $item->id)
            ->whereDate('created_at', $dateSelected)
            ->get();

        if ($checklistRecords->isEmpty()) {
            ChecklistRecord::create([
                'item_id' => $item->id,
                'real_qty' => 0,
                'minus_qty' => 0,
                'status' => null,
                'note' => null,
                'updated_by' => auth()->id(),
                'created_at' => $dateSelected,
                'updated_at' => now(),
            ]);
        }

        return back()->with('success', 'Item P3K berhasil ditambahkan');
    }

    public function updateItem(Request $request, Item $item)
    {
        $item->update([
            'name' => $request->name,
            'standard_qty' => $request->standard_qty,
        ]);

        return back()->with('success', 'Item P3K berhasil diubah');
    }

    public function destroyItem(Item $item)
    {
        $item->delete();

        return response()->json(['message' => 'Item P3K berhasil dihapus']);
    }
}
