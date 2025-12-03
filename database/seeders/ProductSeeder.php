<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Product;
use App\Models\SubkategoriProduct;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure categories exist first
        $subkategories = SubkategoriProduct::all();
        
        if ($subkategories->isEmpty()) {
            $this->command->warn('No subcategories found. Please run KategoriSubkategoriSeeder first.');
            return;
        }

        $brands = ['Panasonic', 'Philips', 'Sharp', 'Toshiba', 'Samsung', 'LG', 'Miyako', 'Cosmos', 'Maspion', 'Sanken', 'Quantum', 'Schneider', 'Broco', 'Uticon', 'Krisbow'];

        $products = [
            // Kipas
            ['name' => 'Kipas Angin Dinding 16 Inch', 'subkategori' => 'Kipas', 'price' => 185000, 'watt' => 45, 'description' => 'Kipas angin dinding dengan 3 kecepatan dan ayunan otomatis'],
            ['name' => 'Kipas Angin Berdiri 18 Inch', 'subkategori' => 'Kipas', 'price' => 275000, 'watt' => 60, 'description' => 'Kipas berdiri dengan tinggi adjustable dan remote control'],
            ['name' => 'Kipas Angin Meja 12 Inch', 'subkategori' => 'Kipas', 'price' => 145000, 'watt' => 35, 'description' => 'Kipas meja compact dengan 2 kecepatan'],
            ['name' => 'Kipas Angin Box Fan 20 Inch', 'subkategori' => 'Kipas', 'price' => 320000, 'watt' => 75, 'description' => 'Box fan industrial dengan aliran udara kuat'],

            // Setrika
            ['name' => 'Setrika Listrik Dry Iron 350W', 'subkategori' => 'Setrika', 'price' => 89000, 'watt' => 350, 'description' => 'Setrika kering dengan lapisan anti lengket'],
            ['name' => 'Setrika Uap Steam Iron 1200W', 'subkategori' => 'Setrika', 'price' => 185000, 'watt' => 1200, 'description' => 'Setrika uap dengan semprotan vertikal'],
            ['name' => 'Setrika Uap Otomatis 2000W', 'subkategori' => 'Setrika', 'price' => 425000, 'watt' => 2000, 'description' => 'Setrika uap dengan sistem auto shut-off'],

            // Raket Nyamuk
            ['name' => 'Raket Nyamuk Elektrik Rechargeable', 'subkategori' => 'Raket Nyamuk', 'price' => 45000, 'watt' => 5, 'description' => 'Raket nyamuk cas ulang dengan lampu LED'],
            ['name' => 'Raket Nyamuk Jumbo 3 Layer', 'subkategori' => 'Raket Nyamuk', 'price' => 65000, 'watt' => 7, 'description' => 'Raket nyamuk jumbo dengan 3 lapisan grid'],

            // Bel Pintu
            ['name' => 'Bel Pintu Wireless 36 Melodi', 'subkategori' => 'Bel Pintu', 'price' => 75000, 'watt' => 3, 'description' => 'Bel pintu nirkabel dengan 36 pilihan nada'],
            ['name' => 'Bel Pintu Digital Voice Record', 'subkategori' => 'Bel Pintu', 'price' => 125000, 'watt' => 5, 'description' => 'Bel pintu digital dengan rekaman suara custom'],

            // Magic Com
            ['name' => 'Rice Cooker 1.2 Liter', 'subkategori' => 'Magic Com', 'price' => 245000, 'watt' => 400, 'description' => 'Rice cooker 6 cups dengan fungsi keep warm'],
            ['name' => 'Rice Cooker Digital 1.8 Liter', 'subkategori' => 'Magic Com', 'price' => 385000, 'watt' => 500, 'description' => 'Rice cooker digital dengan timer dan menu preset'],
            ['name' => 'Rice Cooker Premium 2.0 Liter', 'subkategori' => 'Magic Com', 'price' => 575000, 'watt' => 650, 'description' => 'Rice cooker premium dengan inner pot tebal dan fuzzy logic'],

            // Blender
            ['name' => 'Blender 2 in 1 Kaca', 'subkategori' => 'Blender', 'price' => 195000, 'watt' => 300, 'description' => 'Blender dan dry mill dengan tabung kaca 1.5L'],
            ['name' => 'Blender Plastik 3 Speed', 'subkategori' => 'Blender', 'price' => 145000, 'watt' => 250, 'description' => 'Blender plastik dengan 3 kecepatan'],
            ['name' => 'Blender Juice Extractor', 'subkategori' => 'Blender', 'price' => 285000, 'watt' => 400, 'description' => 'Blender dengan filter juice dan ice crusher'],

            // Mixer
            ['name' => 'Hand Mixer 7 Speed', 'subkategori' => 'Mixer', 'price' => 165000, 'watt' => 200, 'description' => 'Hand mixer 7 kecepatan dengan eject button'],
            ['name' => 'Stand Mixer 5 Liter', 'subkategori' => 'Mixer', 'price' => 625000, 'watt' => 500, 'description' => 'Stand mixer profesional dengan mangkok stainless steel'],

            // Kompor
            ['name' => 'Kompor Gas 2 Tungku', 'subkategori' => 'Kompor', 'price' => 385000, 'watt' => 0, 'description' => 'Kompor gas 2 tungku dengan auto ignition'],
            ['name' => 'Kompor Induksi 1 Tungku', 'subkategori' => 'Kompor', 'price' => 475000, 'watt' => 2000, 'description' => 'Kompor induksi dengan 8 level daya'],
            ['name' => 'Kompor Listrik Portable', 'subkategori' => 'Kompor', 'price' => 295000, 'watt' => 1500, 'description' => 'Kompor listrik portable 1 tungku'],

            // Mug Listrik
            ['name' => 'Mug Elektrik 0.5 Liter', 'subkategori' => 'Mug Listrik', 'price' => 85000, 'watt' => 350, 'description' => 'Mug elektrik stainless steel dengan auto shut-off'],
            ['name' => 'Electric Kettle 1.2 Liter', 'subkategori' => 'Mug Listrik', 'price' => 145000, 'watt' => 500, 'description' => 'Electric kettle dengan indikator lampu'],

            // Kabel
            ['name' => 'Kabel NYA 1.5mm 100m', 'subkategori' => 'Kabel', 'price' => 185000, 'watt' => 0, 'description' => 'Kabel instalasi NYA ukuran 1.5mm roll 100m'],
            ['name' => 'Kabel NYM 2x2.5mm 50m', 'subkategori' => 'Kabel', 'price' => 425000, 'watt' => 0, 'description' => 'Kabel instalasi NYM 2x2.5mm roll 50m'],
            ['name' => 'Kabel Roll Extension 10m', 'subkategori' => 'Kabel', 'price' => 165000, 'watt' => 0, 'description' => 'Kabel roll extension dengan 4 lubang stop kontak'],

            // Klem Kabel
            ['name' => 'Klem Kabel Plastik 10mm isi 100', 'subkategori' => 'Klem Kabel', 'price' => 25000, 'watt' => 0, 'description' => 'Klem kabel plastik diameter 10mm'],
            ['name' => 'Cable Clip Adhesive isi 50', 'subkategori' => 'Klem Kabel', 'price' => 35000, 'watt' => 0, 'description' => 'Klem kabel dengan perekat 3M'],

            // Tali Ties
            ['name' => 'Cable Ties 10cm Hitam isi 100', 'subkategori' => 'Tali Ties', 'price' => 15000, 'watt' => 0, 'description' => 'Cable ties nylon 10cm warna hitam'],
            ['name' => 'Cable Ties 20cm Putih isi 100', 'subkategori' => 'Tali Ties', 'price' => 25000, 'watt' => 0, 'description' => 'Cable ties nylon 20cm warna putih'],

            // Stopkontak
            ['name' => 'Stop Kontak Outbow 4 Lubang', 'subkategori' => 'Stopkontak', 'price' => 35000, 'watt' => 0, 'description' => 'Stop kontak outbow 4 lubang dengan saklar'],
            ['name' => 'Stop Kontak Inbow + USB Charger', 'subkategori' => 'Stopkontak', 'price' => 85000, 'watt' => 0, 'description' => 'Stop kontak inbow dengan 2 port USB'],
            ['name' => 'Universal Socket 6 Lubang', 'subkategori' => 'Stopkontak', 'price' => 125000, 'watt' => 0, 'description' => 'Universal socket 6 lubang dengan proteksi surge'],

            // Saklar
            ['name' => 'Saklar Inbow Single', 'subkategori' => 'Saklar', 'price' => 18000, 'watt' => 0, 'description' => 'Saklar inbow 1 gang warna putih'],
            ['name' => 'Saklar Inbow Double', 'subkategori' => 'Saklar', 'price' => 28000, 'watt' => 0, 'description' => 'Saklar inbow 2 gang warna putih'],
            ['name' => 'Saklar Touch Screen', 'subkategori' => 'Saklar', 'price' => 145000, 'watt' => 0, 'description' => 'Saklar touch screen dengan lampu indikator LED'],

            // Baterai
            ['name' => 'Baterai AA Alkaline isi 4', 'subkategori' => 'Baterai', 'price' => 25000, 'watt' => 0, 'description' => 'Baterai AA alkaline tahan lama'],
            ['name' => 'Baterai AAA Alkaline isi 4', 'subkategori' => 'Baterai', 'price' => 22000, 'watt' => 0, 'description' => 'Baterai AAA alkaline tahan lama'],
            ['name' => 'Baterai 9V Kotak', 'subkategori' => 'Baterai', 'price' => 18000, 'watt' => 0, 'description' => 'Baterai 9V untuk alat elektronik'],

            // Lampu
            ['name' => 'Lampu LED Bulb 9W Putih', 'subkategori' => 'Lampu', 'price' => 22000, 'watt' => 9, 'description' => 'Lampu LED bulb 9W cahaya putih'],
            ['name' => 'Lampu LED Bulb 12W Warm White', 'subkategori' => 'Lampu', 'price' => 28000, 'watt' => 12, 'description' => 'Lampu LED bulb 12W cahaya warm white'],
            ['name' => 'Lampu Neon LED T8 18W', 'subkategori' => 'Lampu', 'price' => 45000, 'watt' => 18, 'description' => 'Lampu neon LED T8 60cm 18W'],
            ['name' => 'Lampu Emergency LED 10W', 'subkategori' => 'Lampu', 'price' => 85000, 'watt' => 10, 'description' => 'Lampu LED emergency dengan baterai rechargeable'],

            // Box MCB
            ['name' => 'Box MCB 4 Group Inbow', 'subkategori' => 'Box MCB', 'price' => 75000, 'watt' => 0, 'description' => 'Box MCB 4 group untuk instalasi inbow'],
            ['name' => 'Box MCB 8 Group Outbow', 'subkategori' => 'Box MCB', 'price' => 125000, 'watt' => 0, 'description' => 'Box MCB 8 group untuk instalasi outbow'],

            // Senter
            ['name' => 'Senter LED Rechargeable', 'subkategori' => 'Senter', 'price' => 45000, 'watt' => 3, 'description' => 'Senter LED cas ulang dengan zoom focus'],
            ['name' => 'Senter Kepala LED Headlamp', 'subkategori' => 'Senter', 'price' => 65000, 'watt' => 5, 'description' => 'Headlamp LED dengan 3 mode cahaya'],
            ['name' => 'Senter Emergency Power Bank', 'subkategori' => 'Senter', 'price' => 125000, 'watt' => 10, 'description' => 'Senter LED dengan power bank 5000mAh'],

            // Remote
            ['name' => 'Remote Universal TV Multi Brand', 'subkategori' => 'Remote', 'price' => 35000, 'watt' => 0, 'description' => 'Remote control universal untuk berbagai merk TV'],
            ['name' => 'Remote AC Universal', 'subkategori' => 'Remote', 'price' => 45000, 'watt' => 0, 'description' => 'Remote control universal untuk AC'],
        ];

        foreach ($products as $productData) {
            // Find subcategory by name
            $subkategori = $subkategories->first(function($sub) use ($productData) {
                return strtolower($sub->name) === strtolower($productData['subkategori']);
            });

            if (!$subkategori) {
                $this->command->warn("Subcategory '{$productData['subkategori']}' not found for product '{$productData['name']}'");
                continue;
            }

            // Generate unique slug
            $slug = Str::slug($productData['name']);
            $originalSlug = $slug;
            $i = 1;
            while (Product::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $i++;
            }

            // Random brand
            $brand = $brands[array_rand($brands)];

            // Random stock between 10-100
            $stock = rand(10, 100);

            // Create specs array
            $specs = [];
            
            if ($productData['watt'] > 0) {
                $specs[] = ['key' => 'Daya', 'value' => $productData['watt'] . ' Watt'];
            }
            
            // Add some random specs
            if (in_array($productData['subkategori'], ['Kipas', 'Setrika', 'Magic Com', 'Blender', 'Mixer', 'Kompor', 'Mug Listrik'])) {
                $specs[] = ['key' => 'Garansi', 'value' => '1 Tahun'];
                $specs[] = ['key' => 'Voltase', 'value' => '220V'];
            }

            if (in_array($productData['subkategori'], ['Kabel', 'Klem Kabel', 'Tali Ties'])) {
                $specs[] = ['key' => 'Material', 'value' => 'Copper/Plastic'];
            }

            if (in_array($productData['subkategori'], ['Lampu'])) {
                $specs[] = ['key' => 'Fitting', 'value' => 'E27'];
                $specs[] = ['key' => 'Garansi', 'value' => '6 Bulan'];
            }

            Product::create([
                'name' => $productData['name'],
                'slug' => $slug,
                'description' => $productData['description'],
                'price' => $productData['price'],
                'stock' => $stock,
                'is_active' => true,
                'image_url' => null, // You can add placeholder images later
                'watt' => $productData['watt'] > 0 ? $productData['watt'] : null,
                'brand' => $brand,
                'subkategori_product_id' => $subkategori->id,
                'specs' => !empty($specs) ? $specs : null,
            ]);

            $this->command->info("Created product: {$productData['name']}");
        }

        $this->command->info('Product seeding completed!');
    }
}
