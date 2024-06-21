<?php

namespace Database\Seeders;

use App\Models\Item;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
            'Perban (lbr 5 cm)',
            'Perban (lbr 10 cm)',
            'Perban (lbr 1,25 cm)',
            'Plester Cepat',
            'Kain Segitiga/mittela',
            'Gunting',
            'Peniti',
            'Sarung Tangan',
            'Masker',
            'Pinset',
            'Lampu Senter',
            'Gelas Cuci Mata',
            'Kantong Plastik Bersih',
            'Aquades (100ml)',
            'Povidon Iodin (60ml)',
            'Alkohol 70%',
            'Buku Panduan P3K',
        ];

        $payloadItems = [];

        foreach ($items as $item) {
            $payloadItems[] = [
                'room_id' => 1,
                'name' => $item,
                'standard_qty' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        Item::insert($payloadItems);
    }
}
