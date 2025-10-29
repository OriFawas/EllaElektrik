<script setup>
import HeaderLayout from '@/components/HeaderLayout.vue'
import FooterLayout from '@/components/FooterLayout.vue'
</script>

<template>
  <div class="min-h-screen flex flex-col bg-[#f5f7fa]">
    <HeaderLayout />

    <main class="flex-1 px-10 py-12">
      <div class="compare-container mx-auto max-w-6xl flex justify-center gap-70">

        <!-- Left Product (Miyako) -->
        <div class="product-block"> <!-- 🚫 HAPUS text-center -->
          <label class="block mb-2 text-gray-600 text-sm">Compare with</label>
          <input 
            type="text" 
            placeholder="🔍 Search product..." 
            class="compare-input"
          />

          <!-- Gambar: rata tengah -->
          <div class="mb-4 flex justify-center">
            <img 
              src="/images/miyako-tjr101.png"
              alt="Kipas Miyako"
              class="product-image"
            />
          </div>

          <!-- Judul & Harga: rata kiri -->
          <p class="product-title">Kipas Miyako</p>
          <p class="product-price">Rp 99</p>

          <!-- SPESIFIKASI: rata kiri -->
          <h3 class="spec-title mt-4">SPESIFIKASI</h3>
          <div class="specs-list mt-2 text-[14px] leading-relaxed text-gray-700 space-y-1 text-left max-w-[220px]">
            <p>• Jumlah Kecepatan: 3 Kecepatan</p>
            <p>• Ukuran: 10 inch</p>
            <p>• Daya: 35 Watt</p>
            <p>• Tegangan: 220 Volt / 50 Hz</p>
            <p>• Garansi Resmi: 1 Tahun</p>
          </div>
        </div>

        <!-- Divider -->
        <div class="divider w-px bg-gray-300"></div>

        <!-- Right Product (Cosmos) -->
        <div class="product-block"> <!-- 🚫 HAPUS text-center -->
          <label class="block mb-2 text-gray-600 text-sm">Compare with</label>
          <input 
            type="text" 
            placeholder="🔍 Search product..." 
            class="compare-input"
          />

          <div class="mb-4 flex justify-center">
            <img 
              src="/images/cosmos-twino16.png"
              alt="Kipas Cosmos"
              class="product-image"
            />
          </div>

          <p class="product-title">Kipas Cosmos</p>
          <p class="product-price">Rp 99</p>

          <h3 class="spec-title mt-4">SPESIFIKASI</h3>
          <div class="specs-list mt-2 text-[14px] leading-relaxed text-gray-700 space-y-1 text-left max-w-[220px]">
            <p>• Bahan Material: Plastik</p>
            <p>• Jumlah Kecepatan: 3 Kecepatan</p>
            <p>• Ukuran: 16 inch</p>
            <p>• Daya: 45 Watt</p>
            <p>• Tegangan: 220 Volt / 50 Hz</p>
            <p>• Garansi Motor: 5 Tahun</p>
          </div>
        </div>

      </div>
    </main>

    <FooterLayout />
  </div>
</template>

<style scoped>
/* Input pencarian */
.compare-input {
  width: 100%;
  max-width: 220px;
  padding: 8px 12px;
  background-color: #d1d5db;
  border: none;
  border-radius: 4px;
  margin-bottom: 16px;
  font-size: 14px;
  color: #333;
}

/* Gambar produk */
.product-image {
  width: 220px;
  height: 220px;
  object-fit: contain;
}

</style>