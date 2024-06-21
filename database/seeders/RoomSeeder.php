<?php

namespace Database\Seeders;

use App\Models\Room;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rooms = [
            'Ruangan Keuangan',
            'Ruangan Aga',
            'Lobby SDM',
            'Ruangan SDM',
            'Lobby Rencana',
            'Ruang Rencana',
            'Lobby Distribusi',
            'Ruang Dan',
            'Ruang UPPK',
            'Pos A',
            'Pos D',
            'Gedung P Batur',
            'Lap. Tenis',
            'Gedung P Bina',
            'K Driver',
            'TPS LB3',
            'Masjid',
        ];

        $payloadRooms = [];

        foreach ($rooms as $room) {
            $payloadRooms[] = [
                'name' => $room,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        Room::insert($payloadRooms);
    }
}
