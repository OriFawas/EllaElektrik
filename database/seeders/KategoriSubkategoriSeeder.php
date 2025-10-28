<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\KategoriProduct;
use App\Models\SubkategoriProduct;

class KategoriSubkategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $map = [
            'Elektronik Rumah Tangga' => [
                'Kipas', 'Setrika', 'Raket Nyamuk', 'Hair Dryer', 'Antena', 'STB', 'Speaker'
            ],
            'Elektronik Dapur' => [
                'Magic Com', 'Blender', 'Mixer', 'Kompor', 'Mug Listrik'
            ],
            'Kelistrikan' => [
                'Kabel', 'Klem Kabel', 'Tali Ties', 'Stopkontak', 'Saklar', 'Kalkulator', 'Baterai', 'Lampu', 'Lampu Tidur', 'Senter'
            ],
        ];

        foreach ($map as $kategoriName => $subkategoriList) {
            $kategori = KategoriProduct::firstOrCreate(
                ['slug' => Str::slug($kategoriName)],
                ['name' => $kategoriName, 'description' => null]
            );

            foreach ($subkategoriList as $subName) {
                SubkategoriProduct::firstOrCreate(
                    ['slug' => Str::slug($kategoriName . ' ' . $subName)],
                    [
                        'kategori_product_id' => $kategori->id,
                        'name' => $subName,
                        'description' => null,
                    ]
                );
            }
        }
    }
}
