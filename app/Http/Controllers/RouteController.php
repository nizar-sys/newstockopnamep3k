<?php

namespace App\Http\Controllers;

use App\Models\ChecklistRecord;
use App\Models\Room;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class RouteController extends Controller
{
    public function dashboard()
    {
        $data = [
            'count_user' => User::count(),
            'count_room' => Room::count(),
            'count_approval' => ChecklistRecord::notVerified()->count(),
        ];

        return view('dashboard.index', compact('data'));
    }

    public function home(Request $request)
    {
        Carbon::setLocale('id');
        $dateSelected = $request->filled('date') ? Carbon::parse($request->date)->format('Y-m-d') : now()->format('Y-m-d');
        $room = $request->filled('room_id') ? Room::with('items.checklistRecords')->findOrFail($request->room_id) : null;
        $qrCode = $room ? QrCode::size(280)->generate(route('landing.checklist', ['room_id' => $room->id, 'date' => $dateSelected])) : null;

        return view('landing.index', compact('dateSelected', 'room', 'qrCode'));
    }

    public function checklistRecords(Request $request)
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
                ->where('status_verif', 'verified')
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

        return view('landing.checklist_records', compact('hasDataset', 'room', 'roomItems', 'dateSelected', 'checklistRecords', 'checklistRecordsUnsaved'));
    }
}
