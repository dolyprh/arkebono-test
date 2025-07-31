<?php

namespace Database\Seeders;

use App\Models\Karyawan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KaryawanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $karyawans = [
            ['npk' => 'K001', 'nama' => 'Ahmad Supriadi', 'alamat' => 'Jakarta Selatan'],
            ['npk' => 'K002', 'nama' => 'Siti Nurhaliza', 'alamat' => 'Jakarta Pusat'],
            ['npk' => 'K003', 'nama' => 'Budi Santoso', 'alamat' => 'Jakarta Barat'],
            ['npk' => 'K004', 'nama' => 'Dewi Sartika', 'alamat' => 'Jakarta Timur'],
            ['npk' => 'K005', 'nama' => 'Rudi Hermawan', 'alamat' => 'Jakarta Utara'],
        ];

        foreach ($karyawans as $karyawan) {
            Karyawan::create($karyawan);
        }
    }
}
