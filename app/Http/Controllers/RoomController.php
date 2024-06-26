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

        $room = Room::create($validated);

        activity('rooms')
            ->causedBy(auth()->user())
            ->performedOn($room)
            ->withProperties([
                'old' => null,
                'new' => $room,
                'changes' => [],
            ])
            ->log('Menambahkan data ruangan');

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
        $oldRoom = clone $room;

        $room->update($validated);

        activity('rooms')
            ->causedBy(auth()->user())
            ->performedOn($room)
            ->withProperties([
                'old' => $oldRoom,
                'new' => $room,
                'changes' => $room->getChanges(),
            ])
            ->log('Mengubah data ruangan');

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
        $oldRoom = clone $room;

        $room->delete();

        activity('rooms')
            ->causedBy(auth()->user())
            ->performedOn($room)
            ->withProperties([
                'old' => $oldRoom,
                'new' => null,
                'changes' => null,
            ])
            ->log('Menghapus data ruangan');

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
            $oldItems = $room->items->toArray();
            $room->items()->delete();
            $room->items()->createMany($payloadItems);

            $room->load('items');
        }

        activity('rooms')
            ->causedBy(auth()->user())
            ->performedOn($room)
            ->withProperties([
                'old' => $oldItems,
                'new' => $room->items->toArray(),
                'changes' => $room->items->toArray(),
            ])
            ->log('Mengubah daftar isi kotak p3k di ruangan ' . $room->name);

        return redirect(route('rooms.show', $room->id))->with('success', 'Daftar isi kotak p3k berhasil diperbarui.');
    }

    public function getRooms(Request $request)
    {
        $search = $request->input('q');

        $products = Room::query()
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', '%' . $search . '%');
            })
            ->latest()
            ->get(['id', 'name']);

        return response()->json($products);
    }
}
