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
            ['name' => 'Kipas Angin Maspion CEF25', 'subkategori' => 'Kipas', 'price' => 185000, 'watt' => 45, 'description' => 'Kipas angin dinding dengan 3 kecepatan dan ayunan otomatis'],
            ['name' => 'Kipas Angin Maspion MV14EX', 'subkategori' => 'Kipas', 'price' => 275000, 'watt' => 60, 'description' => 'Kipas berdiri dengan tinggi adjustable dan remote control'],
            ['name' => 'Kipas Angin Maspion MV18EX', 'subkategori' => 'Kipas', 'price' => 145000, 'watt' => 35, 'description' => 'Kipas meja compact dengan 2 kecepatan'],
            ['name' => 'Kipas Angin Maspion MV300nex', 'subkategori' => 'Kipas', 'price' => 320000, 'watt' => 75, 'description' => 'Box fan industrial dengan aliran udara kuat'],
            ['name' => 'Kipas Angin Duduk Cosmos 9LDA Twino', 'subkategori' => 'Kipas', 'price' => 185000, 'watt' => 45, 'description' => 'Kipas angin dinding dengan 3 kecepatan dan ayunan otomatis'],
            ['name' => 'Kipas Angin Duduk Cosmos 12darn Twino', 'subkategori' => 'Kipas', 'price' => 275000, 'watt' => 60, 'description' => 'Kipas berdiri dengan tinggi adjustable dan remote control'],
            ['name' => 'Kipas Angin Duduk Miyako KAD1227', 'subkategori' => 'Kipas', 'price' => 145000, 'watt' => 35, 'description' => 'Kipas meja compact dengan 2 kecepatan'],
            ['name' => 'Kipas Angin Duduk Miyako KAD1228', 'subkategori' => 'Kipas', 'price' => 320000, 'watt' => 75, 'description' => 'Box fan industrial dengan aliran udara kuat'],
            ['name' => 'Kipas Angin Duduk Cosmos 9LDA', 'subkategori' => 'Kipas', 'price' => 185000, 'watt' => 45, 'description' => 'Kipas angin dinding dengan 3 kecepatan dan ayunan otomatis'],
            ['name' => 'Kipas Angin Karakter Advance BF08', 'subkategori' => 'Kipas', 'price' => 275000, 'watt' => 60, 'description' => 'Kipas berdiri dengan tinggi adjustable dan remote control'],
            ['name' => 'Kipas Angin Miyako Kas1606xpl', 'subkategori' => 'Kipas', 'price' => 145000, 'watt' => 35, 'description' => 'Kipas meja compact dengan 2 kecepatan'],
            ['name' => 'Kipas Angin Miyako Kas1627kbt', 'subkategori' => 'Kipas', 'price' => 320000, 'watt' => 75, 'description' => 'Box fan industrial dengan aliran udara kuat'],
            ['name' => 'Kipas Angin Miyako Kas1618b', 'subkategori' => 'Kipas', 'price' => 185000, 'watt' => 45, 'description' => 'Kipas angin dinding dengan 3 kecepatan dan ayunan otomatis'],
            ['name' => 'Kipas Angin Miyako Kas1629kb', 'subkategori' => 'Kipas', 'price' => 275000, 'watt' => 60, 'description' => 'Kipas berdiri dengan tinggi adjustable dan remote control'],
            ['name' => 'Kipas Angin Miyako klb18', 'subkategori' => 'Kipas', 'price' => 145000, 'watt' => 35, 'description' => 'Kipas meja compact dengan 2 kecepatan'],
            ['name' => 'Kipas Angin Miyako kst18rc', 'subkategori' => 'Kipas', 'price' => 320000, 'watt' => 75, 'description' => 'Box fan industrial dengan aliran udara kuat'],
            ['name' => 'Kipas Angin Miyako tjr-101 pl', 'subkategori' => 'Kipas', 'price' => 320000, 'watt' => 75, 'description' => 'Box fan industrial dengan aliran udara kuat'],

            // Setrika
            ['name' => 'Setrika cosmos CI 3120NJB', 'subkategori' => 'Setrika', 'price' => 89000, 'watt' => 350, 'description' => 'Setrika kering dengan lapisan anti lengket'],
            ['name' => 'Setrika cosmos CI 4110N', 'subkategori' => 'Setrika', 'price' => 185000, 'watt' => 1200, 'description' => 'Setrika uap dengan semprotan vertikal'],
            ['name' => 'Setrika cosmos CIS 318', 'subkategori' => 'Setrika', 'price' => 425000, 'watt' => 2000, 'description' => 'Setrika uap dengan sistem auto shut-off'],
            ['name' => 'Setrika cosmos CIS 428', 'subkategori' => 'Setrika', 'price' => 425000, 'watt' => 2000, 'description' => 'Setrika uap dengan sistem auto shut-off'],
            ['name' => 'Setrika cosmos CIS 438', 'subkategori' => 'Setrika', 'price' => 425000, 'watt' => 2000, 'description' => 'Setrika uap dengan sistem auto shut-off'],
            ['name' => 'Setrika maspion HA 380', 'subkategori' => 'Setrika', 'price' => 425000, 'watt' => 2000, 'description' => 'Setrika uap dengan sistem auto shut-off'],

            // Raket Nyamuk
            ['name' => 'Raket Nyamuk Elektrik Rechargeable', 'subkategori' => 'Raket Nyamuk', 'price' => 45000, 'watt' => 5, 'description' => 'Raket nyamuk cas ulang dengan lampu LED'],

            // Bel Pintu
            ['name' => 'Bell Pintu Richlock', 'subkategori' => 'Bel Pintu', 'price' => 75000, 'watt' => 3, 'description' => 'Bel pintu nirkabel dengan 36 pilihan nada'],
            
            // Magic Com
            ['name' => 'Magic Com 18BH', 'subkategori' => 'Magic Com', 'price' => 245000, 'watt' => 400, 'description' => 'Rice cooker 6 cups dengan fungsi keep warm'],
            ['name' => 'Magic Com Kirin KRC520D', 'subkategori' => 'Magic Com', 'price' => 385000, 'watt' => 500, 'description' => 'Rice cooker digital dengan timer dan menu preset'],
            ['name' => 'Magic Com Miyako 612', 'subkategori' => 'Magic Com', 'price' => 575000, 'watt' => 650, 'description' => 'Rice cooker premium dengan inner pot tebal dan fuzzy logic'],
            ['name' => 'Magic Com Miyako Mc508', 'subkategori' => 'Magic Com', 'price' => 575000, 'watt' => 650, 'description' => 'Rice cooker premium dengan inner pot tebal dan fuzzy logic'],
            ['name' => 'Magic Com Miyako MC606', 'subkategori' => 'Magic Com', 'price' => 575000, 'watt' => 650, 'description' => 'Rice cooker premium dengan inner pot tebal dan fuzzy logic'],
            ['name' => 'Magic Com Yong Ma SMC8017', 'subkategori' => 'Magic Com', 'price' => 575000, 'watt' => 650, 'description' => 'Rice cooker premium dengan inner pot tebal dan fuzzy logic'],

            // Blender
            ['name' => 'Blender Miyako 101PL', 'subkategori' => 'Blender', 'price' => 195000, 'watt' => 300, 'description' => 'Blender dan dry mill dengan tabung kaca 1.5L'],
            ['name' => 'Blender Cosmos CB802', 'subkategori' => 'Blender', 'price' => 145000, 'watt' => 250, 'description' => 'Blender plastik dengan 3 kecepatan'],
            ['name' => 'Blender Miyako BL 301', 'subkategori' => 'Blender', 'price' => 285000, 'watt' => 400, 'description' => 'Blender dengan filter juice dan ice crusher'],
            ['name' => 'Blender Miyako BL102', 'subkategori' => 'Blender', 'price' => 285000, 'watt' => 400, 'description' => 'Blender dengan filter juice dan ice crusher'],
            ['name' => 'Blender Miyako BL152GF', 'subkategori' => 'Blender', 'price' => 285000, 'watt' => 400, 'description' => 'Blender dengan filter juice dan ice crusher'],

            // Mixer
            ['name' => 'Mixer HM 620', 'subkategori' => 'Mixer', 'price' => 165000, 'watt' => 200, 'description' => 'Hand mixer 7 kecepatan dengan eject button'],
            ['name' => 'Mixer Maspion HM 1140', 'subkategori' => 'Mixer', 'price' => 625000, 'watt' => 500, 'description' => 'Stand mixer profesional dengan mangkok stainless steel'],
            ['name' => 'Mixer Maspion MT 1150', 'subkategori' => 'Mixer', 'price' => 625000, 'watt' => 500, 'description' => 'Stand mixer profesional dengan mangkok stainless steel'],

            // Kompor
            ['name' => 'Kompor GMC BM012', 'subkategori' => 'Kompor', 'price' => 385000, 'watt' => 0, 'description' => 'Kompor gas 2 tungku dengan auto ignition'],
            ['name' => 'Kompor Mie Ayam Mawar 202', 'subkategori' => 'Kompor', 'price' => 475000, 'watt' => 2000, 'description' => 'Kompor induksi dengan 8 level daya'],
            ['name' => 'Kompor Mie Ayam Mawar 768', 'subkategori' => 'Kompor', 'price' => 295000, 'watt' => 1500, 'description' => 'Kompor listrik portable 1 tungku'],
            ['name' => 'Kompor Miyako KG 101C', 'subkategori' => 'Kompor', 'price' => 295000, 'watt' => 1500, 'description' => 'Kompor listrik portable 1 tungku'],
            ['name' => 'Kompor Quantum QGC 101 BMP-C', 'subkategori' => 'Kompor', 'price' => 295000, 'watt' => 1500, 'description' => 'Kompor listrik portable 1 tungku'],
            ['name' => 'Kompor Quantum QGC 201 DEP', 'subkategori' => 'Kompor', 'price' => 295000, 'watt' => 1500, 'description' => 'Kompor listrik portable 1 tungku'],
            ['name' => 'Kompor Rinnai Ceflon RI-522 C', 'subkategori' => 'Kompor', 'price' => 295000, 'watt' => 1500, 'description' => 'Kompor listrik portable 1 tungku'],
            ['name' => 'Kompor Rinnai Exotic RI-522 E', 'subkategori' => 'Kompor', 'price' => 295000, 'watt' => 1500, 'description' => 'Kompor listrik portable 1 tungku'],
            ['name' => 'Kompor Rinnai RI-602 BGX', 'subkategori' => 'Kompor', 'price' => 295000, 'watt' => 1500, 'description' => 'Kompor listrik portable 1 tungku'],
            ['name' => 'Kompor Rinnai RI-603 E', 'subkategori' => 'Kompor', 'price' => 295000, 'watt' => 1500, 'description' => 'Kompor listrik portable 1 tungku'],
            ['name' => 'Selang Gas (paket) Caisar', 'subkategori' => 'Kompor', 'price' => 295000, 'watt' => 1500, 'description' => 'Kompor listrik portable 1 tungku'],
            ['name' => 'Selang Gas (paket) Miyako RMS 206M', 'subkategori' => 'Kompor', 'price' => 295000, 'watt' => 1500, 'description' => 'Kompor listrik portable 1 tungku'],
            ['name' => 'Regulator Caisar Smart', 'subkategori' => 'Kompor', 'price' => 295000, 'watt' => 1500, 'description' => 'Kompor listrik portable 1 tungku'],
            ['name' => 'Regulator Starcam SC 112 R', 'subkategori' => 'Kompor', 'price' => 295000, 'watt' => 1500, 'description' => 'Kompor listrik portable 1 tungku'],
            ['name' => 'Regulator Starcam SC 112 RM', 'subkategori' => 'Kompor', 'price' => 295000, 'watt' => 1500, 'description' => 'Kompor listrik portable 1 tungku'],
            ['name' => 'Regulator Starcam SC 112 TM', 'subkategori' => 'Kompor', 'price' => 295000, 'watt' => 1500, 'description' => 'Kompor listrik portable 1 tungku'],
            ['name' => 'Regulator Winn Gas 118NM', 'subkategori' => 'Kompor', 'price' => 295000, 'watt' => 1500, 'description' => 'Kompor listrik portable 1 tungku'],
            ['name' => 'Regulator Winn Gas W118M', 'subkategori' => 'Kompor', 'price' => 295000, 'watt' => 1500, 'description' => 'Kompor listrik portable 1 tungku'],
            ['name' => 'Regulator Winn Gas W181', 'subkategori' => 'Kompor', 'price' => 295000, 'watt' => 1500, 'description' => 'Kompor listrik portable 1 tungku'],
            ['name' => 'Regulator Winn Gas W181M', 'subkategori' => 'Kompor', 'price' => 295000, 'watt' => 1500, 'description' => 'Kompor listrik portable 1 tungku'],

            // Mug Listrik
            ['name' => 'Mug Hyperlite TM15', 'subkategori' => 'Mug Listrik', 'price' => 85000, 'watt' => 350, 'description' => 'Mug elektrik stainless steel dengan auto shut-off'],
            ['name' => 'Mug Listrik Hyperlite Mug TM13', 'subkategori' => 'Mug Listrik', 'price' => 145000, 'watt' => 500, 'description' => 'Electric kettle dengan indikator lampu'],
            ['name' => 'Mug Listrik Q2', 'subkategori' => 'Mug Listrik', 'price' => 145000, 'watt' => 500, 'description' => 'Electric kettle dengan indikator lampu'],
            ['name' => 'Mug Listrik Q2-8014', 'subkategori' => 'Mug Listrik', 'price' => 145000, 'watt' => 500, 'description' => 'Electric kettle dengan indikator lampu'],

            // Kabel
            ['name' => 'Kabel NYA 1.5mm 100m', 'subkategori' => 'Kabel', 'price' => 185000, 'watt' => 0, 'description' => 'Kabel instalasi NYA ukuran 1.5mm roll 100m'],
            ['name' => 'Kabel NYM 2x2.5mm 50m', 'subkategori' => 'Kabel', 'price' => 425000, 'watt' => 0, 'description' => 'Kabel instalasi NYM 2x2.5mm roll 50m'],
            ['name' => 'Kabel Roll Extension 10m', 'subkategori' => 'Kabel', 'price' => 165000, 'watt' => 0, 'description' => 'Kabel roll extension dengan 4 lubang stop kontak'],

            // Klem Kabel
            ['name' => 'Klem Kabel Plastik 10mm isi 100', 'subkategori' => 'Klem Kabel', 'price' => 25000, 'watt' => 0, 'description' => 'Klem kabel plastik diameter 10mm'],
            ['name' => 'Cable Clip Adhesive isi 50', 'subkategori' => 'Klem Kabel', 'price' => 35000, 'watt' => 0, 'description' => 'Klem kabel dengan perekat 3M'],

            // Tali Ties
            ['name' => 'Cable ties Meval 2,5 x 200mm', 'subkategori' => 'Tali Ties', 'price' => 15000, 'watt' => 0, 'description' => 'Cable ties nylon 10cm warna hitam'],
            ['name' => 'Cable ties Powell by MAE 3,6x200mm', 'subkategori' => 'Tali Ties', 'price' => 25000, 'watt' => 0, 'description' => 'Cable ties nylon 20cm warna putih'],
            ['name' => 'Cable ties Cosco 3,6x300', 'subkategori' => 'Tali Ties', 'price' => 25000, 'watt' => 0, 'description' => 'Cable ties nylon 20cm warna putih'],
            ['name' => 'Cable ties Cosco 2,5x150', 'subkategori' => 'Tali Ties', 'price' => 25000, 'watt' => 0, 'description' => 'Cable ties nylon 20cm warna putih'],

            // Stopkontak
            ['name' => 'Stop contact + kabel 5meter Dexta Lubang 2, 3, 4, 5', 'subkategori' => 'Stopkontak', 'price' => 35000, 'watt' => 0, 'description' => 'Stop kontak outbow 4 lubang dengan saklar'],
            ['name' => 'Stop Contact Broco 2,3,4,5 arde', 'subkategori' => 'Stopkontak', 'price' => 85000, 'watt' => 0, 'description' => 'Stop kontak inbow dengan 2 port USB'],
            ['name' => 'Stop contact loyal+saklar (3&4 lubang)', 'subkategori' => 'Stopkontak', 'price' => 125000, 'watt' => 0, 'description' => 'Universal socket 6 lubang dengan proteksi surge'],
            ['name' => 'Stop contact multicord 1,5 meter Yunior lubang 3,4,5)', 'subkategori' => 'Stopkontak', 'price' => 125000, 'watt' => 0, 'description' => 'Universal socket 6 lubang dengan proteksi surge'],
            ['name' => 'Stop contact multicord 3 meter Yunior lubang 2,3,4)', 'subkategori' => 'Stopkontak', 'price' => 125000, 'watt' => 0, 'description' => 'Universal socket 6 lubang dengan proteksi surge'],
            ['name' => 'Stop contact Uticon arde lubang 1, 2, 3, 4, 5, 6', 'subkategori' => 'Stopkontak', 'price' => 125000, 'watt' => 0, 'description' => 'Universal socket 6 lubang dengan proteksi surge'],
            ['name' => 'Stop contact Uticon gepeng lubang 2, 3, 4, 5, 6', 'subkategori' => 'Stopkontak', 'price' => 125000, 'watt' => 0, 'description' => 'Universal socket 6 lubang dengan proteksi surge'],

            // Saklar
            ['name' => 'Saklar Broco', 'subkategori' => 'Saklar', 'price' => 18000, 'watt' => 0, 'description' => 'Saklar inbow 1 gang warna putih'],

            // Baterai
            ['name' => 'Baterai SWAT 3,7 volt ultrafire', 'subkategori' => 'Baterai', 'price' => 25000, 'watt' => 0, 'description' => 'Baterai AA alkaline tahan lama'],
            ['name' => 'Baterai Panasonic A23', 'subkategori' => 'Baterai', 'price' => 22000, 'watt' => 0, 'description' => 'Baterai AAA alkaline tahan lama'],
            ['name' => 'Baterai Panasonic A27', 'subkategori' => 'Baterai', 'price' => 18000, 'watt' => 0, 'description' => 'Baterai 9V untuk alat elektronik'],
            ['name' => 'Baterai button Murata CR2025', 'subkategori' => 'Baterai', 'price' => 18000, 'watt' => 0, 'description' => 'Baterai 9V untuk alat elektronik'],
            ['name' => 'Baterai button Maxell LR1130', 'subkategori' => 'Baterai', 'price' => 18000, 'watt' => 0, 'description' => 'Baterai 9V untuk alat elektronik'],
            ['name' => 'Baterai button Maxell LR41', 'subkategori' => 'Baterai', 'price' => 18000, 'watt' => 0, 'description' => 'Baterai 9V untuk alat elektronik'],
            ['name' => 'Baterai button Maxell LR44', 'subkategori' => 'Baterai', 'price' => 18000, 'watt' => 0, 'description' => 'Baterai 9V untuk alat elektronik'],
            ['name' => 'Baterai button Maxell CR2032', 'subkategori' => 'Baterai', 'price' => 18000, 'watt' => 0, 'description' => 'Baterai 9V untuk alat elektronik'],
            ['name' => 'Baterai button Maxell CR2016', 'subkategori' => 'Baterai', 'price' => 18000, 'watt' => 0, 'description' => 'Baterai 9V untuk alat elektronik'],
            ['name' => 'Baterai button Maxell CR2025', 'subkategori' => 'Baterai', 'price' => 18000, 'watt' => 0, 'description' => 'Baterai 9V untuk alat elektronik'],
            ['name' => 'Baterai button Maxell CR1616', 'subkategori' => 'Baterai', 'price' => 18000, 'watt' => 0, 'description' => 'Baterai 9V untuk alat elektronik'],
            ['name' => 'Baterai Alkaline A3', 'subkategori' => 'Baterai', 'price' => 18000, 'watt' => 0, 'description' => 'Baterai 9V untuk alat elektronik'],
            ['name' => 'Baterai Alkaline A3 4 butir', 'subkategori' => 'Baterai', 'price' => 18000, 'watt' => 0, 'description' => 'Baterai 9V untuk alat elektronik'],
            ['name' => 'Baterai Alkaline A2', 'subkategori' => 'Baterai', 'price' => 18000, 'watt' => 0, 'description' => 'Baterai 9V untuk alat elektronik'],
            ['name' => 'Baterai ABC size D super', 'subkategori' => 'Baterai', 'price' => 18000, 'watt' => 0, 'description' => 'Baterai 9V untuk alat elektronik'],
            ['name' => 'Baterai ABC size D', 'subkategori' => 'Baterai', 'price' => 18000, 'watt' => 0, 'description' => 'Baterai 9V untuk alat elektronik'],
            ['name' => 'Baterai ABC A3 supper power', 'subkategori' => 'Baterai', 'price' => 18000, 'watt' => 0, 'description' => 'Baterai 9V untuk alat elektronik'],
            ['name' => 'Baterai ABC size C super', 'subkategori' => 'Baterai', 'price' => 18000, 'watt' => 0, 'description' => 'Baterai 9V untuk alat elektronik'],
            ['name' => 'Baterai ABC size C', 'subkategori' => 'Baterai', 'price' => 18000, 'watt' => 0, 'description' => 'Baterai 9V untuk alat elektronik'],
            ['name' => 'Baterai ABC A3', 'subkategori' => 'Baterai', 'price' => 18000, 'watt' => 0, 'description' => 'Baterai 9V untuk alat elektronik'],
            ['name' => 'Baterai ABC A2', 'subkategori' => 'Baterai', 'price' => 18000, 'watt' => 0, 'description' => 'Baterai 9V untuk alat elektronik'],

            // Lampu
            ['name' => 'lampu Meval LED Bulb(3,5,7,9,11,13,15,17,19)', 'subkategori' => 'Lampu', 'price' => 22000, 'watt' => 9, 'description' => 'Lampu LED bulb 9W cahaya putih'],
            ['name' => 'lampu Meval LED Capsule(20, 30, 40, 50)', 'subkategori' => 'Lampu', 'price' => 28000, 'watt' => 12, 'description' => 'Lampu LED bulb 12W cahaya warm white'],
            ['name' => 'lampu meval jari(5,8,11,14,18)', 'subkategori' => 'Lampu', 'price' => 45000, 'watt' => 18, 'description' => 'Lampu neon LED T8 60cm 18W'],
            ['name' => 'lampu philips mycare LED (3,4,6,8,12,14 0.5, 19)', 'subkategori' => 'Lampu', 'price' => 85000, 'watt' => 10, 'description' => 'Lampu LED emergency dengan baterai rechargeable'],
            ['name' => 'lampu philips essenstials(5,8,11,14,18,23)', 'subkategori' => 'Lampu', 'price' => 85000, 'watt' => 10, 'description' => 'Lampu LED emergency dengan baterai rechargeable'],
            ['name' => 'lampu hinomaru capsule(5,10,15,20,30,40)', 'subkategori' => 'Lampu', 'price' => 85000, 'watt' => 10, 'description' => 'Lampu LED emergency dengan baterai rechargeable'],
            ['name' => 'lampu Ecolink Capsule(20,25,28,35,38,45)', 'subkategori' => 'Lampu', 'price' => 85000, 'watt' => 10, 'description' => 'Lampu LED emergency dengan baterai rechargeable'],
            ['name' => 'lampu Ecolink Bulb(4,5,7,9,11,15,19)', 'subkategori' => 'Lampu', 'price' => 85000, 'watt' => 10, 'description' => 'Lampu LED emergency dengan baterai rechargeable'],
            ['name' => 'lampu emergency hannoch genius', 'subkategori' => 'Lampu', 'price' => 85000, 'watt' => 10, 'description' => 'Lampu LED emergency dengan baterai rechargeable'],

            // Box MCB
            ['name' => 'Box MCB 4 Group Inbow', 'subkategori' => 'Box MCB', 'price' => 75000, 'watt' => 0, 'description' => 'Box MCB 4 group untuk instalasi inbow'],
            ['name' => 'Box MCB 8 Group Outbow', 'subkategori' => 'Box MCB', 'price' => 125000, 'watt' => 0, 'description' => 'Box MCB 8 group untuk instalasi outbow'],

            // Senter
            ['name' => 'Senter tangan VDR V1622L', 'subkategori' => 'Senter', 'price' => 45000, 'watt' => 3, 'description' => 'Senter LED cas ulang dengan zoom focus'],
            ['name' => 'senter kepala luby 2883l', 'subkategori' => 'Senter', 'price' => 65000, 'watt' => 5, 'description' => 'Headlamp LED dengan 3 mode cahaya'],
            ['name' => 'senter tangan surya SHT320', 'subkategori' => 'Senter', 'price' => 125000, 'watt' => 10, 'description' => 'Senter LED dengan power bank 5000mAh'],
            ['name' => 'senter tangan luby L8908', 'subkategori' => 'Senter', 'price' => 125000, 'watt' => 10, 'description' => 'Senter LED dengan power bank 5000mAh'],
            ['name' => 'senter tangan SWAT SX8008', 'subkategori' => 'Senter', 'price' => 125000, 'watt' => 10, 'description' => 'Senter LED dengan power bank 5000mAh'],
            ['name' => 'senter kepala luby L2875', 'subkategori' => 'Senter', 'price' => 125000, 'watt' => 10, 'description' => 'Senter LED dengan power bank 5000mAh'],
            ['name' => 'senter tangan surya SYT L4W3 COB', 'subkategori' => 'Senter', 'price' => 125000, 'watt' => 10, 'description' => 'Senter LED dengan power bank 5000mAh'],
            ['name' => 'senter tangan luby L8908', 'subkategori' => 'Senter', 'price' => 125000, 'watt' => 10, 'description' => 'Senter LED dengan power bank 5000mAh'],
            ['name' => 'senter kepala mitsuyama ms1922', 'subkategori' => 'Senter', 'price' => 125000, 'watt' => 10, 'description' => 'Senter LED dengan power bank 5000mAh'],

            // Remote
            ['name' => 'Remote AC chunghop', 'subkategori' => 'Remote', 'price' => 35000, 'watt' => 0, 'description' => 'Remote control universal untuk berbagai merk TV'],
            ['name' => 'Remote dvd retive express RM-179 RE', 'subkategori' => 'Remote', 'price' => 45000, 'watt' => 0, 'description' => 'Remote control universal untuk AC'],
            ['name' => 'Remote STB MTX-Apple', 'subkategori' => 'Remote', 'price' => 45000, 'watt' => 0, 'description' => 'Remote control universal untuk AC'],
            ['name' => 'Remote TV C-Vision RM-7860+Poly', 'subkategori' => 'Remote', 'price' => 45000, 'watt' => 0, 'description' => 'Remote control universal untuk AC'],
            ['name' => 'Remote TV eternity chung he', 'subkategori' => 'Remote', 'price' => 45000, 'watt' => 0, 'description' => 'Remote control universal untuk AC'],
            ['name' => 'Remote TV Huayu RM-D1155', 'subkategori' => 'Remote', 'price' => 45000, 'watt' => 0, 'description' => 'Remote control universal untuk AC'],
            ['name' => 'Remote TV max MX609', 'subkategori' => 'Remote', 'price' => 45000, 'watt' => 0, 'description' => 'Remote control universal untuk AC'],
            ['name' => 'Remote TV max MX-smart pns', 'subkategori' => 'Remote', 'price' => 45000, 'watt' => 0, 'description' => 'Remote control universal untuk AC'],
            ['name' => 'Remote TV Visero MR016C', 'subkategori' => 'Remote', 'price' => 45000, 'watt' => 0, 'description' => 'Remote control universal untuk AC'],
            ['name' => 'Remote TV Visero RM580V', 'subkategori' => 'Remote', 'price' => 45000, 'watt' => 0, 'description' => 'Remote control universal untuk AC'],
            ['name' => 'Remote TV Visero RM878', 'subkategori' => 'Remote', 'price' => 45000, 'watt' => 0, 'description' => 'Remote control universal untuk AC'],
            ['name' => 'Remote TV Visero RM2105T', 'subkategori' => 'Remote', 'price' => 45000, 'watt' => 0, 'description' => 'Remote control universal untuk AC'],
            ['name' => 'Remote TV Visero RM2106S', 'subkategori' => 'Remote', 'price' => 45000, 'watt' => 0, 'description' => 'Remote control universal untuk AC'],
            ['name' => 'Remote TV Visero RM3101L', 'subkategori' => 'Remote', 'price' => 45000, 'watt' => 0, 'description' => 'Remote control universal untuk AC'],
            ['name' => 'Remote TV Visero VIO-818S', 'subkategori' => 'Remote', 'price' => 45000, 'watt' => 0, 'description' => 'Remote control universal untuk AC'],
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
