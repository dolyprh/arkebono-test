<?php

namespace Database\Seeders;

use App\Models\Item;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            ['kode' => 'I001', 'nama_item' => 'Roti Tawar', 'harga' => 15000],
            ['kode' => 'I002', 'nama_item' => 'Susu UHT', 'harga' => 8000],
            ['kode' => 'I003', 'nama_item' => 'Mie Instan', 'harga' => 3500],
            ['kode' => 'I004', 'nama_item' => 'Air Mineral', 'harga' => 3000],
            ['kode' => 'I005', 'nama_item' => 'Kopi Sachet', 'harga' => 2500],
            ['kode' => 'I006', 'nama_item' => 'Teh Celup', 'harga' => 1500],
            ['kode' => 'I007', 'nama_item' => 'Gula Pasir', 'harga' => 12000],
            ['kode' => 'I008', 'nama_item' => 'Minyak Goreng', 'harga' => 25000],
        ];

        foreach ($items as $item) {
            Item::create($item);
        }
    }
}
