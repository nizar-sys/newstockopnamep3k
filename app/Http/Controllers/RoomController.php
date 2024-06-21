<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Http\Requests\RequestStoreOrUpdateRoom;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    protected function checkIfHaveNotChanges($request, $room)
    {
        return (count($request->item_name) == $room->items->count()) && ($room->items->pluck('name')->diff($request->item_name)->isEmpty() && $room->items->pluck('standard_qty')->diff($request->item_standard_qty)->isEmpty());
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rooms = Room::orderByDesc('id')->get();

        return view('dashboard.rooms.index', compact('rooms'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.rooms.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RequestStoreOrUpdateRoom $request)
    {
        $validated = $request->validated() + [
            'created_at' => now(),
        ];

        Room::create($validated);

        return redirect(route('rooms.index'))->with('success', 'Data ruangan berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $room = Room::findOrFail($id);

        return view('dashboard.rooms.show', compact('room'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $room = Room::findOrFail($id);

        return view('dashboard.rooms.edit', compact('room'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RequestStoreOrUpdateRoom $request, $id)
    {
        $validated = $request->validated() + [
            'updated_at' => now(),
        ];

        $room = Room::findOrFail($id);

        $room->update($validated);

        return redirect(route('rooms.index'))->with('success', 'Data ruangan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $room = Room::findOrFail($id);

        $room->delete();

        return redirect(route('rooms.index'))->with('success', 'Data ruangan berhasil dihapus.');
    }

    public function updateItems(Request $request, Room $room)
    {
        $payloadItems = [];

        foreach ($request->item_name as $key => $item) {
            $payloadItems[] = [
                'room_id' => $room->id,
                'name' => $item,
                'standard_qty' => $request->item_standard_qty[$key],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        if ($this->checkIfHaveNotChanges($request, $room)) return redirect(route('rooms.show', $room->id))->with('error', 'Daftar isi kotak p3k tidak berubah.');

        if ($payloadItems) {
            $room->items()->delete();
            $room->items()->createMany($payloadItems);
        }

        return redirect(route('rooms.show', $room->id))->with('success', 'Daftar isi kotak p3k berhasil diperbarui.');
    }
}
